import React from 'react'
import { Chart, Axis, Tooltip, Geom } from 'bizcharts'
import { axis, gemo ,width ,height } from '@config/component/Chart'

class ScoreChart extends React.Component {
  render() {
    let {chart} = this.props
    return (
      <Chart width={width} height={height} data={chart} forceFit>
        <Axis name={axis.x} />
        <Axis name={axis.y} />
        <Tooltip crosshairs={{ type: 'rect' }} />
        <Geom {...gemo} />
      </Chart>
    )
  }
}

export default ScoreChart