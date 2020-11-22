import React from 'react'
import { observer } from 'mobx-react'
import { Prompt, StaticRouter } from 'react-router'

import Time from '../../utils/Time'
import MathCAPTCHA from '../../utils/MathCAPTCHA/MathCAPTCHA'
import Route from '../../config/RouteConfig'

import Store from '../../utils/Store'

import './Exam.css'
import RouteConfig from '../../config/RouteConfig'

import cookieManage from '../../utils/cookieManage'



let setQuizNavigator, getQuizUndo

let isDrag = false
let currentY = 0

const buildTimeCount = (milliseconds) => {
  let second = Math.floor((milliseconds % 60000 ) / 1000)
  let hour = Math.floor(milliseconds / 3600000)
  let minute = Math.floor((milliseconds % 3600000) / 60000)
  let mill = milliseconds - 60000 * minute - 1000 * second - 3600000 * hour;
  mill = ('' + mill).substr(0, 3)

  return [hour, minute, second, mill]

}

export const submitAnswer = async () => {
  try {
    await window.showLoading()
    await window.showToast('提交答案中，请不要刷新.....', 1000)
    let func = await Store.request('exam/submit')()
    await func()
  } catch(error) {
    await window.hideLoading()
    let result = Store.infoList.exam_paper.submit()
    cookieManage.deleteStoreCookie()
    cookieManage.storeCookie(result)
    await window.showModal({
      view:
      <>
        <h4>{error.toString()}</h4>
        <p>请确保网络连接稳定，稍后再重新提交</p>
        <p>已经将本次作答记录储存在本地，将会在下次您打开首页的时候自动为您提交</p>
      </>
    })
  }
}

@observer
class Timer extends React.Component {
  constructor(props) {
    super(props)
    this.state = {
      time: Store.infoList.exam_paper.head
    }
  }
  componentWillMount() {
    this.setState({ time: 'launching……' })
  }
  async componentDidMount() {
    await Time.sleep(3000)
    let { duration } = Store.infoList.exam_paper.head
    let foo = (duration, step) => {
      return setInterval(() => {
        let [hour, minute, second, mill] = buildTimeCount(duration)
        this.setState({
          time:  [hour, minute, second]
                  .map(ele => ele < 10 ? '0' + ele : ele)
                  .join(': ') + '.' + mill
        })
        hour === 0 && minute === 5 && second === 0 &&
          window.showModal({
            view:
              <>
                <h2>温馨提示</h2>
                <p>考试时间不足5分钟</p>
                <p>请注意！</p>
              </>
          })
        if (duration <= 0) {
          clearInterval(this.state.interval)
          this.setState({ time: `time's up` })
          let onConfirm = () => promptOFF(async () => {
            await submitAnswer()
            Store.infoList.exam_paper.head.duration = 5
          })
          window.showModal({
            view: <h2>时间到！</h2>,
            onConfirm,
            confirmText: '立即交卷',
            cancelText: '放弃本次考试',
            onCancel: () => promptOFF(() => {
              window.navigateBack()
              window.showToast('已放弃考试！欢迎再战！', 1500, () => window.navigateTo(Route.Student.route))
            })
          })
        }
        duration -= step
      }, step)
    }
    let interval = foo(duration * 60000, 50)
    this.setState({ interval })
  }
  componentWillUnmount() {
    clearInterval(this.state.interval)
  }
  render() {
    return <span>{this.state.time}</span>
  }
}

@observer
class QuizNavigator extends React.Component {
  constructor(props) {
    super(props)
    this.state = {
      array: new Array(this.props.num).fill(0),
      top: 100,
      isShow: true
    }
    this.content = React.createRef();
    setQuizNavigator = (index, value) => {
      let { array } = this.state
      array[index] = value
      this.setState({ array })
    }
    getQuizUndo = () => {
      let undo = this.state.array.reduce((acc, vec) => acc + (vec < 1 ? 1 : 0), 0)
      if (!undo)
        return <></>
      else
        return <p style={{ color: 'red' }}>还有{undo}道题没有作答！</p>
    }
  }
  componentWillMount = () => {
    this.setState({ array: new Array(this.props.num).fill(0) })
  }
  componentDidMount = async () => {
    this.setState({ isShow: true })
    await Time.sleep(2500)
    this.setState({ isShow: false })
  }
  scrollToAnchor = index =>
    () => {
      let anchor = document.getElementById(index)
      anchor && anchor.scrollIntoView({ inline: 'start', behavior: 'smooth' })
    }
  down = e => {
    if (!this.state.isShow)
      return
    isDrag = true
    currentY = e.pageY
  }
  move = e => {
    if (!isDrag)
      return
    let step = e.pageY - currentY, { top } = this.state, { clientHeight } = this.content.current
    top += step
    if (top < 100)
      top = 100
    if (top > window.innerHeight - clientHeight)
      top = window.innerHeight - clientHeight
    this.setState({ top })
    currentY = e.pageY
  }
  up = e => {
    isDrag = false
    currentY = e.pageY
  }
  leave = () => {
    isDrag = false
    this.setState({ isShow: false })
  }
  render() {
    let { array, isShow } = this.state
    const style = value =>
      ({
        'backgroundColor': value ? 'blue' : 'white',
        'color': value ? 'white' : 'rgb(176, 196, 224)'
      })
    const toggle_bn = {
      transform: `scaleX(${isShow ? 1 : -1})`,
    }
    const toggle_style = {
      transform: `translateX(${isShow ? 0 : this.content.current.clientWidth}px)`,
    }
    const content_config = {
      className: 'exam-navigator-content pointer',
      onMouseDown: this.down,
      onMouseUp: this.up,
      onMouseMove: this.move,
      onMouseLeave: this.leave,
      style: {
        top: `${this.state.top}px`
      },
    }
    return (
      <div {...content_config}>
        <div style={toggle_style} onMouseEnter={() => this.setState({ isShow: true })} onClick={() => this.setState({ isShow: !isShow })} ref={this.tab}>
          <div style={toggle_bn}>></div>
        </div>
        <div style={toggle_style} ref={this.content}>
          <div>
            {
              array.map((value, index) =>
                <span key={'exam-navigator-' + index} className='exam-navigator-bn'>
                  <a style={style(value)} onClick={this.scrollToAnchor(index + 1)}>{index + 1}</a>
                  {(index + 1) % 10 ? '' : <br />}
                </span>
              )
            }
          </div>
          <div style={{ marginTop: '10px' }}>
            <div>题数: {array.reduce((acc, val) => acc += val ? 1 : 0)}/{this.props.num}</div>
            <div>剩余时间: <Timer /></div>
            <div>考试时限: {this.props.duration}min</div>
          </div>
        </div>
      </div >
    )
  }
}

@observer
class Quiz extends React.Component {
  copy = event => {
    let text = document.getSelection().toString()
    event.clipboardData.setData('text/plain',
      `${text}`)
    event.preventDefault()
  }
  render() {
    let { index, quiz } = this.props
    let { topic, type } = quiz
    let items, func, origin = ['A', 'B', 'C']
    if (type == 'sc') {
      origin.push('D')
      type = 'radio'
      func = char => () => {
        quiz.stu_answer = char
        quiz.time_stamp = new Date().getTime()
        setQuizNavigator(index, 1)
      }
    } else {
      ['D', 'E', 'F'].forEach(char => quiz[`obj${char}`] && quiz[`obj${char}`] != '' && origin.push(char))
      type = 'checkbox'
      quiz.stu_answer = []
      func = char => () => {
        const answer_set = new Set(quiz.stu_answer)
        let value = answer_set.has(char)
        quiz.time_stamp = new Date().getTime()
        if (value)
          answer_set.delete(char)
        else
          answer_set.add(char)
        quiz.stu_answer = [...answer_set].sort()
        setQuizNavigator(index, answer_set.size > 0)
      }
    }
    items = origin.map(char =>
      <div className='exam-item' key={quiz.id + char}>
        <label className='pointer'>
          <input type={type} name={quiz.id} value={char} onClick={func(char)} />
          <span>{quiz[`obj${char}`]}</span>
        </label>
      </div>
    )
    return (
      <div className='exam-content-quiz'>
        <p id={index + 2} onCopy={this.copy}>{index + 1}.{topic && topic.split('').map(t => <span>{t}</span>)}</p>
        <form name={quiz.id}>{items.sort(() => Math.random() - .5)}</form>
      </div>
    )
  }
}

@observer
class Subjective extends React.Component {
    handleChange = (e) => {
        let { quiz } = this.props
        quiz.stu_answer = e.target.value.trim()
    }

    handlePaste = (e) => {
        e.preventDefault()
        window.showToast('不能粘贴哦')
    }

    render() {
        let { index, quiz } = this.props
        let { topic } = quiz
        return (
            <div className='exam-content-sub'>
              <p id={index + 2}>{index + 1}.{ topic }</p>
              <textarea
                className='exam-sub-textarea'
                rows='10'
                onChange={ this.handleChange }
                onPaste= { this.handlePaste }
              >

              </textarea>
            </div>
        )
    }
}

const previewGuide = (
  <div>
    <h2>试卷预览导引</h2>
    <p>预览试卷随机出题随机排序效果</p>
    <p>体验学生的做题过程及感受</p>
    <p>若满意预览效果，可点击卷尾按钮立刻发布试卷</p>
    <p>若要继续编辑试卷，可点击卷尾按钮返回编辑页面</p>
  </div>
)

let promptOFF

@observer
class Exam extends React.Component {
  constructor(props) {
    super(props)
    const { body, subjective } = Store.infoList.exam_paper
    this.state = {
      isCAPTCHA: false,
      blur_on: false,
      prompt_on: false,
      face_on: true,
      question: body.reduce((acc, val) => [...acc, ...val.question], []).shuffle(),
      subjective: subjective.reduce((acc, val) => [...acc, ...val.question], []).shuffle()
    }
    promptOFF = callback => this.setState({ prompt_on: false }, callback)

  }
  abandon = () => {
    if (Store.state.status === 'college') {
      window.navigateBack()
      return
    }
    window.showModal({
      view: <h2>确认放弃考试?</h2>,
      onConfirm: async () => {
        this.setState({ prompt_on: false })
        window.showToast('已放弃考试！欢迎再战！', 1500, () => window.navigateTo(Route.Student.route))
        await submitAnswer()
      }
    })
  }
  submit = () => {
    if (this.state.isCAPTCHA) {
      // Store.state.status === 'student'
      if (true) {
        window.showModal({
          view:
            <>
              <h2>确认提交试卷?</h2>
              {getQuizUndo()}
            </>
          ,
          onConfirm: async () => {
            this.setState({ prompt_on: false })
            await submitAnswer()

          }
        })
      } else {
        window.navigateTo(RouteConfig.SelStu.route)
      }
    } else {
      window.showToast('请填写正确的验证信息', 1750)
    }
  }
  rejected = async () => {
    this.setState({ prompt_on: false })
    await window.hideMask()
    window.navigateBack()
    await window.showToast('已放弃本次考试')
  }
  componentWillMount() {
    switch (Store.state.status) {
      case 'college':
        window.showModal({ view: previewGuide })
        break
      case 'student':
        this.setState({ prompt_on: true, blur_on: true })
        window.onbeforeunload = () => '离开页面将不会记录作答进度'
        break
      default:
        this.setState({ prompt_on: true, blur_on: true })
        window.onbeforeunload = () => '离开页面将不会记录作答进度'
    }
    this.setState({ face_on: true })
    Store.state.inExam = true
  }
  componentWillUnmount() {
    this.setState({ blur_on: false, prompt_on: false })
    window.onbeforeunload = () => { }
        Store.state.inExam = false
    }
  render() {
    const { head } = Store.infoList.exam_paper
    let question = this.state.question
    let subjective = this.state.subjective
    let status = Store.state.status !== 'college'
    let submit_config = {
      className: `iw-bn iw-${this.state.isCAPTCHA ? 'confirm' : 'cancel'}`,
      onClick: this.submit,
      style: {
        transition: 'all .6s'
      }
    }
    return (
      <div className='exam-container'>
        {this.state.prompt_on && <Prompt message='将不会保留您的作答情况' />}
        <QuizNavigator num={head.num} duration={head.duration} />
        <div className='exam-content'>
          <div>
            <h2 id={1}>{head.title}</h2>
            <hr />
            <div className='row aside'>
              <span>题目数量:{head.num}</span>
              <span>考试开始时间:{new Date().toLocaleString()};答题时限:{head.duration}min</span>
            </div>
          </div>
          <div>
            {
              question.map((quiz, index) => <Quiz key={`quiz-${index}`} quiz={quiz} index={index} />)
            }
            {
              subjective.map((quiz, index) => <Subjective key={`sub-${index}`} quiz={quiz} index={question.length + index} />)
            }
          </div>
        </div>
        <div className='exam-submit'>
          <div className='pointer'>
            <MathCAPTCHA onCorrect={() => this.setState({ isCAPTCHA: true })} seed={233} />
          </div>
          <div className='row center'>
            <p>
              <span {...submit_config}>{status ? '提交考试' : '发布试卷'}</span>
              <span className='responsive iw-bn iw-cancel' onClick={this.abandon}>{status ? '放弃考试' : '返回'}</span>
            </p>
          </div>
        </div>
      </div>
    )
  }
}

export default Exam
