const statusFilter = targetStatus => ({
  text:targetStatus,
  func:item => targetStatus === item.status
})

export const filters = [
  {
    text: '全部试卷',
    func: () => true
  },
  ...['未发布','已发布','已完成'].map(statusFilter)
]

export const sorts = [
  {
    text: '从旧到新',
    func: (a, b) => dateSort(a, b)
  },
  {
    text: '从新到旧',
    func: (a, b) => -dateSort(a, b)
  },
  {
    text: '随机',
    func: () => Math.random() - .5
  },
]

const dateSort = (a, b) => [a, b].map(vec => vec.date.split('-')).mutiply([[1,-1]])[0].reduce((acc, num) => acc !== 0 ? acc : num, 0)
//const dateSort = (a, b) => [a, b].map(elm => elm.date.split('-')).transpose().reduce((acc, vec) => acc !== 0 ? acc : vec[0] - vec[1], 0)