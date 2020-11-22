import React from 'react'
import { observer } from 'mobx-react'

import './EditCard.css'

@observer
class Item extends React.Component {
  render() {
    let { index, name, type, question } = this.props,
      item = question.option(index),
      items = question.options()
    const onSel = () => {
      if (type === 'mc') {
        item.value = !item.value
        return
      }
      items.forEach(elem => elem.value = false)
      item.value = true
    }
    if (type === 'sc' && index > 3)
      return <></>
    return (
      <div className='ec-item'>
        <input className='pointer' name={name} type={type === 'sc' ? 'radio' : 'checkbox'} value={index} onChange={onSel} checked={item.value ? 'checked' : ''} />
        <span>{String.fromCharCode(65 + index)}</span>
        <input maxLength='60' placeholder='在此输入选项' className='input' value={item.text} onChange={e => item.text = e.target.value} />
        {type === 'mc' && items.length > 3 && <img className='pointer' title='删除选项' src={require('../assets/delete.png')} onClick={() => question.remove(index)} />}
      </div>
    )
  }
}


@observer
class EditCard extends React.Component {
  constructor(props) {
    super(props)
    this.state = {
      title_height: 0,
      textarea_ref: React.createRef(),
      item_height: 0,
      textarea_ref_item: React.createRef()
    }
  }
  shrink = () => {
    let { title_height } = this.state
    title_height  = 0
    this.setState({ title_height },  this.autoH)
  }

  shrink_item = () => {
    let { item_height } = this.state
    item_height  = 0
    this.setState({ item_height },  this.autoH)
  }
  textarea_input = e => {
    let { question } = this.props, oldL = question.title.length, newL = e.target.value.length
    question.setTitle(e)
    if (newL < oldL) {
      this.shrink()
      return
    }
    this.autoH()
  }

  textarea_input_item = e => {
    let { question } = this.props, oldL = question.item.length, newL = e.target.value.length
    question.setItem(e)
    if (newL < oldL) {
      this.shrink()
      return
    }
    this.autoH()
  }

  autoH = () => {
    let { title_height, textarea_ref } = this.state, { current } = textarea_ref
    if (title_height < current.scrollHeight)
      this.setState({ title_height: current.scrollHeight + 3 })
  }
  componentDidMount() {
    this.autoH()
  }
  rpl = e => {
    let char = e.which || e.keyCode
    char === 13 && e.preventDefault()
  }
  render() {
    let
      { question, section, id } = this.props,
      type = section.config.type,
      keyo = `${question}-${id}`,
      { textarea_ref, title_height, textarea_ref_item, item_height } = this.state,
      ta_style = title_height > 0 ? { height: `${title_height}px` } : {},
      xx_style = item_height > 0 ? { height: `${item_height}px` } : {}
    return (
      <div className='ec-content'>
        <div className='ec-head row'>
          <span>{id + 1}.</span>
          <textarea ref={textarea_ref} style={ta_style} placeholder='在此输入题干' onKeyPress={this.rpl} onMouseOver={this.shrink}
            onMouseEnter={this.autoH} onChange={this.textarea_input} value={question.title} />
          <img className='pointer' title='删除本题目' src={require('../assets/rubbish.png')} onClick={() => section.remove(id)} />
        </div>
        <div className='ec-items'>
            {
                type === 'sc' || type === 'mc'
                ?   <form>
                        {question.options().map((item, i) => <Item key={keyo + '-' + i} type={type} question={question} index={i} name={keyo} />)}
                    </form>
                : <textarea ref={textarea_ref_item} style={xx_style} placeholder='在此输入答案' onKeyPress={this.rpl} onMouseOver={this.shrink_item}
                onMouseEnter={this.autoH} onChange={this.textarea_input_item} value={question.item} />
            }

          {type === 'mc' && question.options().length < 6 && <div className='ec-add-item pointer' title='添加一个新选项' onClick={() => question.add()}>+添加选项+</div>}
        </div>
      </div>
    )
  }
}


export default EditCard
