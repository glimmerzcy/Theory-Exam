import React, { useState, useCallback } from 'react'
import { post } from '../../utils/request'
import './CollegeLogin.css'
import Store from '../../utils/Store'
import Route from '../../config/RouteConfig'

const CollegeLogin = () => {
    const [userName, setUserName] = useState('')
    const [passWord, setPassWord] = useState('')

    const handleSubmit = useCallback((from) => {
        const postData = async () => {
            let response = await post('api/college/login/v1', from)
            if (response.status === 'succeed') {
                Store.state.status = 'college'
                window.navigateTo(Route.College.route)
            } else {
                window.showToast(response.message)
            }
        }
        postData()
    })

    const config = [
        {
            name: 'userName',
            text: '用户名',
            option: {
                value: userName,
                onChange: useCallback((e) => setUserName(e.target.value), [])
            }

        },
        {
            name: 'passWord',
            text: '密码',
            option: {
                type: 'password',
                value: passWord,
                onChange: useCallback((e) => setPassWord(e.target.value), [])
            }

        }
    ]

    const finish = useCallback(() => {
        let data = {
            userName,
            passWord
        }
        handleSubmit(data)
    }, [userName, passWord])

    return (
        <div className='login-from'>
            <div className='fil-stu-er-head'>学院登陆</div>
            <div className='from' >
                <form>
                    {
                        config.map((ele, i) => (

                                <div className='row aside' key={i}>
                                    <span>{ele.text}</span>
                                    <div>
                                        <input {...ele.option} />
                                    </div>
                                </div>
                        ))
                    }
                </form>
            </div>
            <button className='iw-bn iw-confirm' onClick={finish} type="submit">登陆</button>
        </div>
    )
}

export default CollegeLogin
