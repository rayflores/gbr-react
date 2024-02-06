import React from 'react'

function Shimmer(props) {
    const count = props.count;
    if ( count === 1 ) {
        return (
            <div className="image-shimmer single">
                {Array(1).fill("")
                    .map((e,index)=>(<div key={index} className='shimmer-card'></div>))
                }
            </div>
        )
    } 
    if ( count > 1 ) {
        return (
            <div className="image-shimmer multi">
            {Array(props.count).fill("")
                .map((e,index)=>(<div key={index} className='shimmer-card'></div>))
            }
            </div>
        )
    }
}
export default Shimmer