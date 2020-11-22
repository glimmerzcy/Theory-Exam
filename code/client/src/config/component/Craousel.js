export function showingStyle(showing) {
  return {
    opacity: showing ? 1 : .5,
    filter: `blur(${showing ? 0 : 3}px)`
  }
}

export function dot_config(i,page,keys){
  return {
    style:{
      width: `${i === page ? 15 : 6}px`
    },
    key:`${keys}-dots-${i}`,
    className:'responsive info-slider-dot pointer'
  }
}

export const long = 10;