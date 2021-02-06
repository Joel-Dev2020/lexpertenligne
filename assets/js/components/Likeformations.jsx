import React from 'react';
import ReactDOM from 'react-dom'

class Likeformations extends React.Component{

    state = {
        likes: this.props.likes || 0,
        isLiked: this.props.isLiked || false
    };

    handleClick = () => {
        const isLiked = this.state.isLiked;
        const likes = this.state.likes + (isLiked ? -1 : 1);

        this.setState({likes, isLiked: !isLiked})
    }

    render() {
        return <a className="btn-link text-primary" onClick={this.handleClick}>
            {this.state.likes} &nbsp;
            <i className={this.state.isLiked ? "fas fa-thumbs-up" : "far fa-thumbs-up"}></i> &nbsp;
            {this.state.isLiked ? "Je n'aime plus!" : "J'aime"}
        </a>
    }
}

document.querySelectorAll('small.formation-react-like').forEach(function (element) {
    ReactDOM.render(<Likeformations />, element)
})
