import React from 'react'
import Slider from 'react-slick'
import './Banner.css'
import {settings} from '../config/component/Banner'

class Banner extends React.Component{
  componentDidMount(){
    Array.from(document.getElementsByClassName('slick-arrow')).forEach(item => item.remove())
  }
  render(){
    return (
      <div className='banner'>
        <Slider {...settings}>
          {new Array(4).map((ele, index) => <img key={index} src={require('../assets/banner.png')} alt='banner'/>)}
        </Slider>
      </div>
    );
  }
}

export default Banner
