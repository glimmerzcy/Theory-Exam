import React from 'react'
import { BlockMath } from 'react-katex'
import 'katex/dist/katex.min.css'
import './MathCAPTCHA.css'

const random = (min, max) => Math.floor(Math.random() * (max - min + 1) + min)

let data = new Array(5).fill(0)
let seed = 0

function newFormula() {
  data = data.map(i => random(1, 5))
  let [a, , k1, k2, k3] = data
    return `${a}\\times${k1}+${k2}-${k3}=?`
}

function getResult() {
  let [a, , k1, k2, k3] = data
    return a * k1 + k2 - k3
}

class MathCAPTCHA extends React.Component {
  constructor(props) {
    super(props)
    this.state = { formula: newFormula() }
    this.onChanged = this.onChanged.bind(this)
    this.newFormula = this.newFormula.bind(this)
  }
  onChanged(e) {
    if (e.target.value === getResult())
      this.props.onCorrect()
  }
  newFormula() {
    let formula = newFormula()
    this.setState({ formula })
  }
  render() {
    if (this.props.seed !== seed) {
      seed = this.props.seed
      this.newFormula()
    }
    return (
      <div className='captcha-container'>
        <div onClick={this.newFormula}>
          <BlockMath>{this.state.formula}</BlockMath>
          <p>不会做？点击换一个</p>
        </div>
        <div className='row center'>
          <span>验证信息:</span>
          <input placeholder='请输入计算结果' onChange={this.onChanged} />
        </div>
      </div>
    )
  }
}

export default MathCAPTCHA