import React from "react"
import Time from "../Time"

import { aniDuration, restDuration, loadSVG } from "./config"
import "./InnerWindow.css"

export default class extends React.Component {
    constructor(props) {
        super(props)
        this.state = {
            content: "",
            isShow: false,
            closed: true,
            type: ""
        }
        let afterRender
        window.waitIW = async () => await Time.sleep(aniDuration + restDuration)
        window.iwType = () => this.state.type
        window.closeIW = async (force = false) => {
            if (this.state.type === "mask" && !force) return
            this.setState({ isShow: false })
            await Time.sleep(aniDuration)
            this.setState({ closed: true, type: "" })
            await Time.sleep(restDuration)
        }
        window.showToast = async (
            content,
            duration = 1500,
            callback = undefined
        ) => {
            await Time.sleep(this.state.isShow ? aniDuration + restDuration : 0)
            try {
                this.setState({
                    content: <h2>{content}</h2>,
                    closed: false,
                    type: "toast"
                })
            } catch (e) {
                throw e
            }
            await Time.sleep(restDuration)
            this.setState({ isShow: true })
            await Time.sleep(duration)
            await window.closeIW()
            callback && callback()
        }
        window.showMask = async content =>
            await window.showView(content, "mask")
        window.hideMask = async () => await window.closeIW(true)
        window.showView = async (content, type = "", important = false) => {
            if (this.state.type === "model" && !important) return
            await Time.sleep(this.state.isShow ? aniDuration + restDuration : 0)
            this.setState({ content, closed: false, type })
            await Time.sleep(restDuration)
            this.setState({ isShow: true })
            await Time.sleep(aniDuration)
        }
        window.showModal = async (data, important = false) => {
            let { view, onConfirm, onCancel, confirmText, cancelText } = data
            let ok = async () => {
                await window.closeIW()
                onConfirm && onConfirm()
            }
            let no = async () => {
                await window.closeIW()
                onCancel && onCancel()
            }
            let content = (
                <>
                    <div className="iw-container">
                        <div>{view}</div>
                    </div>
                    <div className="row around">
                        <div
                            className="responsive iw-bn iw-confirm"
                            onClick={ok}
                        >
                            {confirmText || "确认"}
                        </div>
                        <div
                            className="responsive iw-bn iw-cancel"
                            onClick={no}
                        >
                            {cancelText || "取消"}
                        </div>
                    </div>
                </>
            )
            await window.showView(content, "model", important)
        }
        window.showLoading = async (func = () => {}) => {
            afterRender = func
            await window.showView(loadSVG, "loading")
        }
        window.hideLoading = async () => {
            await window.closeIW()
            afterRender && (await afterRender())
        }
    }
    static autoHideLoading(target) {
        target.prototype.componentDidUpdate = async function() {
            if (window.iwType() === "loading") await window.hideLoading()
        }
    }
    render() {
        let { isShow, closed, type, content } = this.state
        let style = {
            opacity: isShow ? 1 : 0,
            display: closed ? "none" : "flex"
        }
        let inner_config = {
            className: `g-i-w g-i-w-${type}`,
            style: {
                opacity: isShow ? 1 : 0,
                transform: `scale(${isShow ? 1 : 0})`
            }
        }
        return (
            <div className="g-i-w-container" style={style}>
                <div {...inner_config}>{content}</div>
            </div>
        )
    }
}
