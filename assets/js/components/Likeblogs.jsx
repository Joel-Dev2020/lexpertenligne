import React from 'react';
import ReactDOM from 'react-dom'

class Likeblogs extends React.Component{

    state = {
        likes: this.props.likes || 0,
        isLiked: this.props.isLiked || false,
        user: this.props.user || false
    };

    handleClick = () => {
        const isLiked = this.state.user ? this.state.isLiked : '';
        const likes = this.state.user ? this.state.likes + (isLiked ? -1 : 1) : '';

        this.state.user ? this.setState({likes, isLiked: !isLiked}) : '';
    }

    handleConnectUser = () => {
        alert("Veuillez vous connecter avec de liker")
    }

    render() {
        return <a className="btn-link text-primary" onClick={this.state.user ? this.handleClick : this.handleConnectUser}>
            {this.state.likes} &nbsp;
            <i className={this.state.isLiked ? "fas fa-thumbs-up" : "far fa-thumbs-up"}></i> &nbsp;
            {this.state.isLiked ? "Je n'aime plus!" : "J'aime"}
        </a>
    }
}

document.querySelectorAll('small.blog-react-like').forEach(function (element) {
    const user = +element.dataset.user === 1
    const likes = +element.dataset.likes
    const isLiked = +element.dataset.isliked === 1
    console.log(user)
    ReactDOM.render(<Likeblogs likes={likes} isLiked={isLiked} user={user} />, element)
})
