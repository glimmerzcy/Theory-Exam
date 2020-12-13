import React from "react"
import { observer } from "mobx-react"
import { score_text, score_text_color } from "@config/page/Index"
import "./index.css"

import InfoList from "@components/InfoList/InfoList"
import Title from "@components/InfoList/InfoTitle"
import InfoCraousel from "@components/InfoList/InfoCraousel"
import Banner from "@components/Banner"

import Store from "@utils/Store"
import checkPlatform from "@utils/checkPlatform"
import cookieManage from "@utils/cookieManage"
import { submitAnswer } from "../Exam"

@observer
class Index extends React.Component {
    constructor(props) {
        super(props)
        this.state = {
            slider_tab: 0
        }
    }
    sliderTab = k => () => {
        this.setState({ slider_tab: k })
    }
    componentDidMount = () => {
        Store.request("infoList/getInfo")()
        let foo = string => {
            window.showModal({ view: <h4>{string}</h4> })
        }
        checkPlatform(foo)

        let bar = async () => {
            window.showModal({
                view: (
                    <h4>
                        检测到本地储存有上次未提交成功的记录,
                        点击确认将重新提交，点击取消将作废本次答题
                    </h4>
                ),
                onConfirm: submitAnswer,
                onCancel: () => cookieManage.deleteStoreCookie()
            })
        }
        if (cookieManage.cookieProxyInstance.submitData0) {
            bar()
        }
    }
    render() {
        let tab = this.state.slider_tab
        return (
            <div>
                <Banner />
                <div className="row aside">
                    <div>
                        <div className="row aside">
                            <div id="index-notice">
                                <InfoList
                                    title="通知公告"
                                    more="/notice?id=0"
                                    assign={Store.infoList.notice}
                                />
                            </div>
                            <div id="index-studata">
                                <InfoList
                                    title="学习资料"
                                    more="~https://learning.twtstudio.com/"
                                    assign={Store.infoList.resource}
                                />
                            </div>
                        </div>
                        <div id="index-exam">
                            <InfoList
                                title="考试"
                                assign={Store.infoList.exam}
                            />
                        </div>
                    </div>
                    <div className="page-craousel">
                        <Title title="考试情况" />
                        <div className="score-texts row aside">
                            <span
                                className="responsive score-text pointer"
                                style={{ color: score_text_color[tab ? 1 : 0] }}
                                onClick={this.sliderTab(0)}
                            >
                                {score_text[0]}
                            </span>
                            <span className="score-text">/</span>
                            <span
                                className="responsive score-text pointer"
                                style={{ color: score_text_color[tab ? 0 : 1] }}
                                onClick={this.sliderTab(1)}
                            >
                                {score_text[1]}
                            </span>
                        </div>
                        <div className="score-slider">
                            <InfoCraousel
                                keys={score_text[0].text}
                                list={
                                    tab
                                        ? Store.infoList.stu_score_list
                                              .rankByTime
                                        : Store.infoList.stu_score_list
                                              .rankByScore
                                }
                            />
                        </div>
                    </div>
                </div>
            </div>
        )
    }
}

export default Index
