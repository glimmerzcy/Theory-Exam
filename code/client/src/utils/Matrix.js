Array.prototype.shuffle = function () {
  return this.sort(() => Math.random() - .5)
}

Array.prototype.unlucky = function () {
  return this[Math.floor(Math.random() * this.length)]
}

Array.prototype.transpose = function () {
  return this[0].map((col, j) => this.map(row => row[j]))
}

Array.prototype.mutiply = function (matrix) {
  let result = []
  const m = this[0].length
  const n = matrix.length
  const l = this.length
  for (let j = 0; j < n; j++) {
    result[j] = []
    for (let i = 0; i < m; i++) {
      result[j][i] = 0
      for (let k = 0; k < l; k++)
        result[j][i] += this[k][i] * matrix[j][k]
    }
  }
  return result
}
