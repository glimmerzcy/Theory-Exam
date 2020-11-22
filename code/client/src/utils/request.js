import React from 'react'

// export const BASE_URL = 'https://theory.twt.edu.cn'
export const BASE_URL = 'https://localhost:8000'

const defaultGET = {
    method: 'GET',
    credentials: 'include'
}

const networkError =
    <>
        <h1>网络异常</h1>
        <p>请稍后再试</p>
        <small>Pray for our server solemnly</small>
    </>

function toString(obj) {
    let s = []
    Object.keys(obj).forEach(i => s.push(`${i}=${obj[i]}`))
    return s.join('&')
}

async function request(url, setting, isJSON) {
    try {
        let res = await fetch(url, setting)
        if (!res.ok) {
            if (res.status >= 500) {
                throw new Error(res.status + ": 请换成校园网再试试呢")
            }
            throw res.status
        }
        if (isJSON) res = await res.json()
        if (res.error_code) {
            window.showToast(res.message)
        }
        return res
    } catch (error) {
        if (window.iwType() === 'loading') {
            await window.waitIW()
            window.iwType() === 'loading' && await window.hideLoading()
            await window.showModal({
                view: networkError
            })

            return
        }

        throw error
    }

}

export async function get(targetURL, params, isJSON = true) {
    let url = `${BASE_URL}/${targetURL}`
    params && (url += `?${toString(params)}`)
    console.log('get', url)
    return await request(url, defaultGET, isJSON)
}

export async function post(targetURL, params = {}, isJSON = true) {
    let url = `${BASE_URL}/${targetURL}`
    console.log('post', url, params)
    let setting = {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        credentials: 'include',
        body: JSON.stringify(params)
    }
    return await request(url, setting, isJSON)
}

//bind to window for test
window.get = get
window.post = post
