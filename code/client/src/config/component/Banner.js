import React from 'react'

export const settings = {
  dots: true,
  infinite: true,
  pauseOnHover: true,
  draggable: false,
  adaptiveHeight: true,
  autoplay: true,
  autoplaySpeed: 5000,
  arrow: false,
  slidesToShow: 1,
  slidesToScroll: 1,
  appendDots: dots => (
    <div>
      <ul style={{ marginTop: '-75px' }}> {dots} </ul>
    </div>
  )
}