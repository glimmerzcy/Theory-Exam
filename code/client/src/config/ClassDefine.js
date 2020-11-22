import { observable } from 'mobx'
import XLSX from 'xlsx'
import Time from '../utils/Time'

const af = 'ABCDEF'

window.XLSX = XLSX

export class Option {
  constructor(text = '', value = false) {
    this.text = text
    this.value = value
  }
  @observable
  text = ''
  @observable
  value = false
}

export class SubQuestion {
    @observable
    id = undefined
    @observable
    is_exist = 1
    @observable
    title = ''

    @observable
    item = ''

    static EQ(id) {
      let Q = new SubQuestion()
      Q.id = id
      Q.is_exist = 0
      console.log(Q.is_exist)
      return Q
    }
    setTitle(e) {
      if (this.title == e.target.value)
        return
      this.title = e.target.value
    }

    setItem(e) {
        if (this.item == e.target.value)
          return
        this.item = e.target.value
    }

    static parseFromDBQ(que) {
        let question = new SubQuestion()
        question.title = que.topic
        question.id = que.id
        question.is_exist = que.is_exist
        question.item = que.answer
        return question
    }

    static parseFromRow(que) {
        let question = new SubQuestion()
        question.title = que['题干']
        question.is_exist = 1
        question.item = que['答案']
        return question
    }
  }


export class Question {
  @observable
  id = undefined
  @observable
  is_exist = 1
  @observable
  title = ''
  @observable
  item = [
    new Option(''),
    new Option(''),
    new Option('', true),
    new Option(''),
  ]
  static EQ(id) {
    let Q = new Question()
    Q.id = id
    Q.is_exist = 0
    console.log(Q.is_exist)
    return Q
  }
  setTitle(e) {
    if (this.title == e.target.value)
      return
    this.title = e.target.value
  }
  option = index => this.item[index]
  options = () => this.item
  setOption = (index, value) => this.item[index] = value
  add(option = new Option()) {
    this.item.push(option)
  }
  remove = index => this.item.splice(index, 1)
  getOptTextObj() {
    let obj = {}
    this.options().forEach((opt, k) => obj[`obj${af[k]}`] = opt.text)
    if (this.options.length < af.length) {
        for (let i = this.option.length; i < af.length; ++i) {
            obj[`obj${af[i]}`] = ''
        }
    }
    return obj
  }
  getOptTextArr(ori) {
    let arr = new Array(ori.length).fill('')
    this.item.forEach((opt, i) => arr[i] = opt.text)
    return arr
  }
  getKeyStr() {
    let arr = []
    this.item.forEach((opt, i) => opt.value && arr.push(af[i]))
    arr = arr.join('')
    if (arr === '' && this.is_exist) throw '不允许没有正确答案'
    return arr
  }
  static parseFromRow(que, opts) {
    let question = new Question()
    question.title = que['题干']
    question.is_exist = 1
    opts.split('').forEach((opt, k) => que[opt] && question.setOption(k, new Option(que[opt], que['答案'].indexOf(opt) >= 0)))
    return question
  }
  static parseFromDBQ(que) {
    let question = new Question()
    question.title = que.topic
    question.id = que.id
    question.is_exist = que.is_exist
    let ans = new Array(af.length).fill(0)
    let answer = que.answer.split('')
    let A = 'A'.charCodeAt()
    for (let a of answer) {
        ans[a.charCodeAt() - A]++;
    }
    af.split('').forEach((opt, k) => {
        que[`obj${opt}`] && question.setOption(k, new Option(que[`obj${opt}`], ans[opt.charCodeAt() - A] === 1))
    })
    return question
  }
}

export class Section {
  constructor(type = 'sc', subtitle = '单选题') {
    this.config.type = type
    this.config.subtitle = subtitle
    this.quiz = this.config.type === 'sub' ? [new SubQuestion()] : [new Question()]
    if (type === 'sc') {
      this.config.num = 40
      this.config.point = 1
    } else if (type === 'mc') {
      this.config.num = 30
      this.config.point = 2
    } else {
        this.config.num = 2
        this.config.point = 10
    }
  }
  @observable
  config = {
    type: '',
    subtitle: '单选题',
    point: 1,
    num: 1
  }
  @observable
  quiz
  quesion = index => this.quiz[index]
  quesions = () => this.quiz
  add = (quesion = this.config.type === 'sub' ? new SubQuestion() : new Question()) => this.quiz.push(quesion)
  calculateScore = () => {
    let
      { quiz, config } = this,
      { point, num } = config,
      score = quiz.reduce((acc, vec) => acc + vec.is_exist * point, 0),
      sup = point * num
    return Math.min(score, sup)
  }
  remove = id => {
    console.log('remove', id)
    console.log(this.quiz.filter(q => q.is_exist))
    let target = this.quiz.filter(q => q.is_exist)[id]
    console.log('target', target)
    target.is_exist = 0
    console.log(this.quiz.filter(q => q.is_exist))
  }
}

const sheet_names = ['单选', '多选'], opts = ['ABCD', af], type_names = 'sm'
const sheet_head = i => ['题干', ...opts[i].split(''), '答案']

// subjective
const subjective_sheet_names = ['主观']
const subjective_sheet_head = i => ['题干', '答案']

export class Paper {
  @observable
  head = {
    title: '新未命名试卷',
    'time-limit': 35,
    'min-score': 60,
    'max-times': 10,
    'date-from': '1976-10-01T00:00',
    'date-to': '2035-10-01T23:59',
    'more-detail': '更多细节...',
    'tip': '无',
    'aim': 0
  }
  @observable
  body = [
    new Section('sc', '单选题'),
    new Section('mc', '多选题'),
    new Section('sub', '主观题')
  ]
  section = index => this.body[index]
  calculateScore = () => this.body.reduce((acc, vec) => acc + vec.calculateScore(), 0)
  calculateNum = () => this.body.reduce((acc, vec) => acc + vec.config.num * 1, 0)
  canToExam = () => this.body.reduce((acc, sec) => acc && (sec.quesions().length >= sec.config.num), true)
  resetSoftly = i => this.body[i].quiz = []
  renew = () => {
    this.head.id = undefined
    this.body.forEach(sec =>
      sec.quiz.forEach(que => que.id = undefined)
    )
  }
  parseFromFile = async e => {
    try {
        let paper = new Paper()
        for (let i = 0; i < sheet_names.length; i++) {
            paper.resetSoftly(i)
            for (let que of this.section(i).quiz)
            que.id && paper.section(i).add(Question.EQ(que.id))
        }
        for (let i = 0; i < subjective_sheet_names.length; i++) {
            paper.resetSoftly(i + sheet_names.length)
            for (let que of this.section(i + sheet_names.length).quiz)
            que.id && paper.section(i + sheet_names.length).add(SubQuestion.EQ(que.id))
        }
        let sheets = XLSX.read(new Uint8Array(e.target.result), { type: 'array' }).Sheets
        for (let i = 0; sheet_names[i]; i++) {
            let ques = XLSX.utils.sheet_to_json(sheets[sheet_names[i]])
            if (!ques[0]) throw '格式不正确'
            for (let que of ques) {
                paper.section(i).add(Question.parseFromRow(que, opts[i]))
                await Time.sleep(1)
            }
        }
        for (let i = 0; subjective_sheet_names[i]; i++) {
            let ques = XLSX.utils.sheet_to_json(sheets[subjective_sheet_names[i]])
            if (!ques || !ques[0]) break;

            for (let que of ques) {
                paper.section(i + sheet_names.length).add(SubQuestion.parseFromRow(que))
                await Time.sleep(1)
            }
        }

      this.body = paper.body
      window.hideLoading()
      return { msg: true }
    } catch (msg) {
      window.hideLoading()
      return { msg }
    }
  }
  static parseFromDB = async (res, id, papers) => {
    res = res.data
    let target_paper = res.head
    let paper = new Paper()
    paper.head = {
      id,
      title: target_paper.name,
      'time-limit': target_paper.duration,
      'date-from': Time.stamp_to_datetime(target_paper.started_at),
      'date-to': Time.stamp_to_datetime(target_paper.ended_at),
      'max-times': target_paper.test_time,
      'tip': target_paper.tip,
      'related': target_paper.related,
      'aim': target_paper.aim
    }
    let data = res.body
    let len = data.length
    for (let i = 0; i < len; i++) {
      let ques = data[i].questions
      await paper.resetSoftly(i)
      if (data[i].name !== 'tizu3') {
        for (let que of ques) {
            paper.section(i).add(Question.parseFromDBQ(que))
            await Time.sleep(1)
        }
      } else {
        for (let que of ques) {
            paper.section(i).add(SubQuestion.parseFromDBQ(que))
            await Time.sleep(1)
        }
      }

    }
    return paper
  }

  parseToExcel() {
    let wb = XLSX.utils.book_new()
    sheet_names.forEach((name, i) =>
      XLSX.utils.book_append_sheet(wb, XLSX.utils.aoa_to_sheet([sheet_head(i),
      ...this.body[i].quesions().filter(q => q.is_exist).map(que =>
        [que.title, ...que.getOptTextArr(opts[i]), que.getKeyStr()]
      )]), name)
    )

    subjective_sheet_names.forEach((name, i) =>
        XLSX.utils.book_append_sheet(wb, XLSX.utils.aoa_to_sheet([subjective_sheet_head(i),
        ...this.body[i + sheet_names.length].quesions().filter(q => q.is_exist).map(que =>
        [que.title, que.item]
        )]), name)
    )

    return wb
  }
  getExamHead = () => ({
    head: {
      title: this.head.title,
      start_date: Date.now(),
      duration: this.head['time-limit'],
      num: this.calculateNum()
    }
  })
  getAUpLoad() {
    if (this.calculateScore() === 0) throw '不允许试卷为空'
    let len = this.body.length
    for (let i = 0; i < len - 1; ++i) {
        let sec = this.body[i]
        for (let quiz of sec.quesions()) {
            if (!quiz.is_exist) continue
            if (quiz.title === '') throw '不允许题目为空'
            else if (sec.config.type != 'sub') {
                for (let opt of quiz.options())
                    if (opt.text === '') throw '不允许选项为空'
            }
        }
    }
    let obj = {}
    obj.head = {
      id: this.head.id,
      name: this.head.title,
      test_time: this.head["max-times"],
      duration: this.head["time-limit"],
      tip: this.head["tip"],
      aim: this.head["aim"],
      started_at: Time.datetime_to_stamp(this.head["date-from"]),
      ended_at: Time.datetime_to_stamp(this.head["date-to"]),
      related: 0
    }
    obj.body = [
        {
            name: 'tizu1',
            credit_per_question: 2,
            draw_quantity: this.body[0].quesions().length,
            is_subjective: 0,
            questions: []
        },
        {
            name: 'tizu2',
            credit_per_question: 3,
            draw_quantity: this.body[1].quesions().length,
            is_subjective: 0,
            questions: []
        },
        {
            name: 'tizu3',
            credit_per_question: 5,
            draw_quantity: this.body[2].quesions().length,
            is_subjective: 1,
            questions: []
        }]
    this.body.forEach((sec, index) => {
        if (sec.config.type !== 'sub') {
            sec.quesions().map(que =>
                obj.body[index].questions.push({
                    topic: que.title,
                    ...que.getOptTextObj(),
                    answer: que.getKeyStr()
                })
            )
        } else {
            sec.quesions().map(que =>
                obj.body[index].questions.push({
                    topic: que.title,
                    answer: que.item
                })
            )
        }

    })
    return obj
  }
  getAExam() {
    let exam = this.getExamHead()
    exam.body = this.body.reduce((acc, sec) =>
      [...acc, ...sec.quesions().shuffle().slice(-sec.config.num).map((que, k) =>
        ({
          id: `temp_${sec.config.type}_${k}`,
          type: sec.config.type,
          ...que.getOptTextObj(),
          question: que.title
        })
      )]
      , []).shuffle()
    return exam
  }
}

export class Exam {
  @observable
  head = {
    title: new String(),
    start_date: new String(),
    duration: new String(),
    num: new Number()
  }
  @observable
  body = [
    {
      question: []
    }
  ]
  @observable
  subjective = [
    {
      question: []
    }
  ]
  submit() {
    let result = {
      paper_id: this.head.paper_id,
      record: [],
      sub_record: []
    }
    console.log(this.body)
    this.body.concat(this.subjective).forEach(subj => {
      let block = {
        tag_id: subj.tag_id,
        answers: subj.question.reduce((acc, val) => {
          acc[val.id] = Array.isArray(val.stu_answer) ? val.stu_answer.join('') : val.stu_answer
          return acc
        }, {})
      }
      if (+subj.is_subjective) {
          result.sub_record.push(block);
      } else {
          result.record.push(block);
      }
    //   let { stu_answer, time_stamp, type, id } = que
    //   type === 'mc' && (stu_answer = stu_answer.join(''))
    //   result[`${type}q`][id] = stu_answer || ''
    //   result[`${type}q_t`][id] = time_stamp || 0
    })
    return result
  }
  cheat() {
    return { paper_id: this.head.paper_id, mcq: {}, scq: {} }
  }
}
