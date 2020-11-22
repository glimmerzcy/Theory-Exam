import React from 'react'
import { observer } from 'mobx-react'
import './InfoItem.css'

@observer
class InfoItem extends React.Component {
    render() {
        let {url,info,time} = this.props.assign
        let container = {
            className:'info-item row aside'
        }
        if(url){
            container.onClick = () => window.navigateTo(url)
            container.style = {
                cursor:'pointer'
            }
        }
        return (
            <div {...container}>
                <div className='row'>
                    <span className='info-item-char'>▶</span>
                    <span className='info-item-text'>{info}</span>
                </div>
                <span className='info-item-text'>{time}</span>
            </div>
        )
    }
}

export default InfoItem