import React from 'react'
import './Footer.css'

export default class Footer extends React.Component {
    render() {
        return (
            <div className='footer'>
                <div>欢迎加群
                &nbsp; 738064793
                进行问题反馈</div>
                <div>我们将第一时间为您解答</div>
                <div>Copyright 2020 TWTStudio. All rights reserved.</div>
                <small id='foo'>Developers: 张瑞安 樊一飞 江一东  Directors: 张瑞桁 黄千慧 王兆盟  Designer: 庄菲菲</small>
            </div>
        )
    }
}
