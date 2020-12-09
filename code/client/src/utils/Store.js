import React from "react"
import { Link } from "react-router-dom"
import { observable } from "mobx"
import storable from "./ProtoStore"
import { Paper } from "../config/ClassDefine"

@storable
class Store {
    @observable state = {
        status: "unknown",
        userName: "",
        inExam: false
    }
    @observable infoList = {
        //通知公告
        notice: [

        ],
        //学习资料
        resource: [
            {
                info: "党课理论学习",
                time: "2018-11-16",
                url: "~https://www.twt.edu.cn/party/?page=study&do=text"
            },
            {
                info: "学习强国",
                time: "2018-11-16",
                url: "~https://www.xuexi.cn/"
            }
        ],
        //考试信息列表
        exam: [
            
        ],
        //我的考试
        mypaper: [[], [], []],
        stu_score_list: {
            rankByTime: [["rankByTime", "***", 99]],
            rankByScore: [["301****063", "***", 99]]
        },
        //学院历次考试
        all_paper: [
            {
                title: "自动化学院第三十六期形式与政策课网上测试",
                date: "2018-11-16",
                status: "已完成",
                num: 1,
                rate: 45
            },
            {
                title: "自动化学院第三十六期形式与政策课网上测试",
                date: "2018-11-17",
                status: "已完成",
                num: 2134,
                rate: 1
            },
            {
                title: "自动化学院第三十六期形式与政策课网上测试",
                date: "2018-11-15",
                status: "已发布",
                num: 2134,
                rate: 45
            },
            {
                title: "自动化学院第三十六期形式与政策课网上测试",
                date: "2018-12-15",
                status: "未发布",
                num: 2134,
                rate: 45
            },
            {
                title: "自动化学院第三十六期形式与政策课网上测试",
                date: "2018-10-15",
                num: 2134,
                rate: 45
            },
            {
                title: "自动化学院第三十六期形式与政策课网上测试",
                date: "2017-10-15",
                num: 2134,
                rate: 45
            },
            {
                title: "自动化学院第三十六期形式与政策课网上测试",
                date: "2019-10-15",
                num: 2134,
                rate: 45
            }
        ],
        stu_table: [],
        stu_ids: {},
        stu_table_filter: new Array(9).fill([]),
        all_stus: [],
        tableFilter: [],
        all_college: [{ id: 1, name: "加载中...." }],
        college_codes: [],
        //当前正在考试的试卷
        exam_paper: {
            head: {
                start_date: Date.now(),
                duration: 120,
                num: 70
            },
            body: [
                {
                    question: []
                }
            ],
            subjective: [
                {
                    question: []
                }
            ]
        },
        //当前正在编辑的试卷
        paper: new Paper(),
        paper_detail: {
            name: "no name"
        }
    }
}

export default new Store()
