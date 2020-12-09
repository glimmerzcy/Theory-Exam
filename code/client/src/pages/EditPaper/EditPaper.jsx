import React, { useState } from "react"
import { observer } from "mobx-react"

import "./EditPaper.css"
import "../../utils/InnerWindow/InnerWindow"
import Store from "../../utils/Store"

import Editer from "../../components/EditCard"
import { func_icon } from "../../config/page/EditPaper"
import ConfigModel from "../../components/PaperConfig/index"
import Time from "../../utils/Time"
import "../../utils/InnerWindow/InnerWindow"
import "../../components/PaperConfig/index.css"

@observer
class ConfigSection extends React.Component {
    constructor(props) {
        super(props)
        const { index } = props
        this.state = {
            index,
            config: {
                ...Store.infoList.paper.section(index).config
            }
        }
    }
    static getDerivedStateFromProps(nextProps, prevState) {
        const { index } = nextProps;
        if (index !== prevState.index) {
            return {
                index,
                config: {
                    ...Store.infoList.paper.section(index).config
                }
            }
        }
        return null;
    }


    config = {
        subtitle: {
            check: v => !!v,
            placeholder: ""
        },
        point: {
            check: v => v < 100 && v > 0,
            placeholder: "请输入0<x<100数字"
        },
        num: {
            check: v => v > 0,
            placeholder: "请输入"
        }
    }
    setClass = (key, isNum = false) => ({
        key,
        onChange: this.onInput(key),
        value: this.state.config[key],
        placeholder: this.config[key].placeholder,
        onKeyPress: e => {
            let char = e.which || e.keyCode
            if (isNum && (char < 48 || char > 75)) e.preventDefault()
        }
    })
    onInput = key => e => {
        let { value } = e.target
        let { state } = this
        if (state.config[key] === value) return
        state.config[key] = this.config[key].check(value) ? value : ""
        this.setState(state)
    }
    close = () => {
        window.closeIW()
    }
    submit = async () => {
        //submit check will be done at here
        const { index, onError } = this.props
        Store.infoList.paper.section(index).config = { ...this.state.config }
        const keys = Object.keys(this.config)
        for (let key of keys) {
            if (this.state.config[key] === "") {
                onError()
                return
            }
            if (!this.config[key].check(this.state.config[key])) {
                onError(this.config[key].placeholder)
            }
        }
        let { onConfirm } = this.props
        onConfirm && onConfirm()
        window.closeIW()
    }
    render() {
        let { setClass } = this
        return (
            <div>
                <div>
                    <input
                        className="ep-ip-title"
                        minLength="1"
                        maxLength="20"
                        value="编辑题组"
                    />
                    <div className="row aside ep-ip-inner">
                        <div className="column aside">
                            <div>题组名称</div>
                            <div>每题分数</div>
                            <div>出题数目</div>
                        </div>
                        <div className="column aside">
                            <input {...setClass("subtitle")} type="text" />
                            <input
                                {...setClass("point", true)}
                                type="number"
                                min={1}
                                max={100}
                            />
                            <input
                                {...setClass("num", true)}
                                defaultValue="1"
                                type="number"
                            />
                        </div>
                    </div>
                </div>
                <p className="row around">
                    <div
                        className="responsive iw-bn iw-confirm"
                        onClick={this.submit}
                    >
                        保存设置
                    </div>
                    <div
                        className="responsive iw-bn iw-cancel"
                        onClick={this.close}
                    >
                        取消
                    </div>
                </p>
            </div>
        )
    }
}

@observer
class GM extends React.Component {
    constructor(props) {
        super(props)
        if (!this.state) this.state = {}
        this.state[this.props.index] = {
            ...Store.infoList.paper.body[props.index].config
        }
    }
    setClass = key => {
        return {
            onChange: this.onInput(key),
            value: this.state[this.props.index][key],
            min: 1,
            type: "number"
        }
    }
    onInput = key => {
        return e => {
            if (this.state[this.props.index][key] !== e.target.value) {
                let { state } = this
                state[this.props.index][key] = e.target.value
                this.setState(state)
            }
        }
    }
    close = () => {
        this.setState()
        this.state[this.props.index] = {
            ...Store.infoList.paper.body[this.props.index].config
        }
        this.setState(this.state)
        window.closeIW()
    }
    submit = () => {
        Store.infoList.paper.body[this.props.index].config = {
            ...this.state[this.props.index]
        }
        calculateScore()
        window.closeIW()
    }
    render() {
        let { subtitle, type } = Store.infoList.paper.body[
            this.props.index
        ].config
        return (
            <div className="column aside ep-im-container">
                <p className="row aside">
                    <div className="column aside">
                        <div>标题</div>
                        <div>分值</div>
                    </div>
                    <div className="column aside">
                        <input value={subtitle} disabled />
                        <input {...this.setClass("point")} />
                    </div>
                    <div className="column aside">
                        <div>题目类型</div>
                        <div>出题数量</div>
                    </div>
                    <div className="column aside">
                        <select value={type} disabled>
                            <option value="sc">单选</option>
                            <option value="mc">多选</option>
                        </select>
                        <input {...this.setClass("num")} />
                    </div>
                </p>
                <p className="row around">
                    <div className="iw-bn iw-confirm" onClick={this.submit}>
                        确定
                    </div>
                    <div className="iw-bn iw-cancel" onClick={this.close}>
                        取消
                    </div>
                </p>
            </div>
        )
    }
}

export const GroupModel = GM

let calculateScore

@observer
class EditPaper extends React.Component {
    constructor(props) {
        super(props)
        this.state = {
            group: 0,
            score: 2
        }
        calculateScore = () => {
            let { score } = this.state,
                current = Store.infoList.paper.calculateScore()
            score !== current && this.setState({ score: current })
        }
        this.fileSelector = React.createRef()
    }
    componentWillMount() {
        calculateScore()
    }
    add = async () => {
        const { paper } = Store.infoList,
            { group } = this.state

        paper.section(group).addQuestion()
        await Time.sleep(10)
        document
            .querySelector(".ep-add-quiz")
            .scrollIntoView({ inline: "end", behavior: "smooth" })
    }
    upload = async () => {
        window.showModal({
            view: (
                <>
                    <h2>确认导入</h2>
                    <p>导入内容将覆盖现有内容</p>
                </>
            ),
            onConfirm: Store.request(
                "excel/loadin",
                () => this.fileSelector.current.files[0]
            )
        })
    }

    changeTitle = index => () => {
        let onError = async msg => {
            await window.closeIW()
            window.showModal({
                view: (
                    <>
                        <h2>保存失败</h2>
                        <p style={{ color: "red" }}>{msg || "不允许为空"}</p>
                    </>
                ),
                onConfirm: () =>
                    window.showView(<ConfigSection index={index} />)
            })
        }
        window.showView(<ConfigSection index={index} onError={onError} />)
    }

    addType = () => {
        Store.infoList.paper.addSub()
    }

    render() {
        calculateScore()
        const { paper } = Store.infoList,
            { head, body } = paper
        let { group, score } = this.state,
            targets = paper
                .section(group)
                .quesions()
                .filter(q => q && q.is_exist),
            editers = targets.map((q, i) => (
                <Editer question={q} section={paper.section(group)} id={i} />
            ))
        return (
            <div className="row aside">
                <div className="ep-l">
                    <div className="ep-quiz-card">
                        <div onClick={() => window.showView(<ConfigModel />)}>
                            <div className="pointer">题组编辑</div>
                            <div>当前分数:{score}</div>
                        </div>
                    </div>
                    {body.map((content, i) => (
                        <div
                            key={`eqc-${i}`}
                            className={
                                group === i
                                    ? "pointer column ep-quiz-card ep-quiz-card-ed"
                                    : "pointer column ep-quiz-card"
                            }
                            onClick={() => this.setState({ group: i })}
                        >
                            <div className="ep-quiz-card-text">
                                {content.config.subtitle}
                            </div>
                            <div
                                className="ep-quiz-card-edit"
                                onClick={this.changeTitle(i)}
                            >
                                编辑题组
                            </div>
                        </div>
                    ))}
                    <div className="ep-quiz-card" onClick={this.addType}>
                        <img src={require("../../assets/add-bright.png")} />
                    </div>
                </div>
                <div className="ep-r">
                    <div className="row aside">
                        <div>试卷题目：{head.title}</div>
                        <div className="ep-func-bar">
                            {func_icon.map(item => (
                                <img
                                    {...{
                                        className: "pointer",
                                        key: item.icon,
                                        onClick: item.click,
                                        src: require(`../../assets/${item.icon}.png`),
                                        alt: item.alt,
                                        title: item.title
                                    }}
                                />
                            ))}
                        </div>
                    </div>
                    <div className="ep-paper">
                        <div className="file-label">
                            <label className="responsive">
                                导入文件
                                <input
                                    type="file"
                                    name="file"
                                    ref={this.fileSelector}
                                    multiple={false}
                                    accept=".xls,.xlsx"
                                    onChange={this.upload}
                                    onClick={e => (e.target.value = null)}
                                />
                            </label>
                            <span
                                className="responsive"
                                onClick={Store.request("excel/loadout")}
                            >
                                导出文件
                            </span>
                        </div>
                        <div>
                            {editers}
                            <div
                                title="添加一道新题目"
                                className="responsive ep-add-quiz"
                                onClick={this.add}
                            >
                                <img
                                    src={require("../../assets/add-char.png")}
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        )
    }
}

export default EditPaper
