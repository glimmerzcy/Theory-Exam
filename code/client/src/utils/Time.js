export default class {
  static sleep = duration => new Promise(resolve => setTimeout(resolve, duration))
  static stamp_to_datetime(stamp) {
    let [input, Y, M, D, h, m] = stamp.match(/(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):\d{2}/)
    return `${Y}-${M}-${D}T${h}:${m}`
  }
  static datetime_to_stamp(datetime) {
    let [input, Y, M, D, h, m] = datetime.match(/(\d{4})-(\d{2})-(\d{2})T(\d{2}):(\d{2})/)
    return `${Y}-${M}-${D} ${h}:${m}:00`
  }
}