import {render, unmountComponentAtNode} from 'react-dom'
import React, {useCallback, useEffect, useRef, useState} from 'react'
import {useFetch, usePaginatedFecth} from "./hoocks";
import {Field} from "./Form";

const dateFormat = {
    dateStyle: 'medium',
    timeStyle: 'short'
}

const VIEW = 'VIEW'
const EDIT = 'EDIT'

function Comments({blog, user, avatar}) {
    const {items: comments, load, setItems: setComment, loading, count, hasMore} = usePaginatedFecth('/api/commentairesblogs?blogs=' + blog)
    const addComment = useCallback(comment => {
        setComment(comments => [comment, ...comments])
    }, [])
    const deleteComment = useCallback(comment => {
        setComment(comments => comments.filter(c => c !== comment))
    }, [])
    const updateComment = useCallback((newComment, oldComment) => {
        setComment(comments => comments.map(c => c === oldComment ? newComment : c))
    }, [])

    useEffect(() => {
        load()
    }, [])
    return <div>
        {user && <CommentForm blog={blog} user={user} onComment={addComment} />}
        <br/>
        {loading && 'Chargement...'}
        <Title count={count} />
        {comments.map(c =>
            <Comment
                key={c.id}
                comment={c}
                avatar={avatar}
                canEdit={c.user.id === user}
                onDelete={deleteComment}
                onUpdate={updateComment}
            />
            )}
        {hasMore && <button disabled={loading} onClick={load} className="btn btn-block btn-light">Charger les commentaires...</button>}
    </div>
}

const Comment = React.memo(({comment, avatar, onDelete, canEdit, onUpdate}) => {
    //Variables
    const date = new Date(comment.createdAt)

    //Events
    const toggleEdit = useCallback(() => {
        setState(state => state === VIEW ? EDIT : VIEW)
    }, [])
    const onDeleteCallback = useCallback(() => {
        onDelete(comment)
    }, [comment])
    const onComment = useCallback((newComment) => {
        onUpdate(newComment, comment)
        toggleEdit()
    }, [comment])

    //Hoocks
    const [state, setState] = useState(VIEW)
    const {loading: loadingDelete, load: callDelete} = useFetch(comment['@id'], 'DELETE', onDeleteCallback)

    //Rendu
    return <div className="ml-sm-32pt mt-3 card p-3">
        <div className="d-flex">
            <a href="#" className="avatar avatar-sm mr-12pt">
                <img src={avatar} width="40" height="40" alt="" className="avatar-img rounded-circle"/>
            </a>
            <div className="flex">
                <div className="d-flex align-items-center">
                    <a href="#" className="text-body"><strong>{comment.user.fullname}</strong></a>
                    <small className="ml-auto text-muted">{date.toLocaleString(undefined, dateFormat)}</small>
                </div>
                {state === VIEW ?
                    <p className="mt-1 text-70">
                        {comment.message}
                    </p> :
                    <CommentForm comment={comment} onComment={onComment} onCancel={toggleEdit} />
                }

                <div className="d-flex align-items-center">
                    <a href="#" className="text-50 d-flex align-items-center text-decoration-0">
                        <i className="material-icons mr-1" style={{ fontSize: 'inherit' }}>favorite_border</i> 3
                    </a>
                    <a href="#" className="text-50 d-flex align-items-center text-decoration-0 ml-3">
                        <i className="material-icons mr-1" style={{ fontSize: 'inherit' }}>thumb_up</i> 13
                    </a>
                    {(canEdit && state !== EDIT) &&
                        <span style={{ width: '100%' }} className="d-flex align-items-center">
                            <a className="text-50 d-flex align-items-center text-decoration-0 ml-3" onClick={callDelete.bind(this, null)} disabled={loadingDelete}>
                                <i className="fa fa-trash mr-1" style={{ fontSize: 'inherit' }}></i> Supprimer
                            </a>
                            <a className="text-50 d-flex align-items-center text-decoration-0 ml-3" onClick={toggleEdit}>
                                <i className="fa fa-edit mr-1" style={{ fontSize: 'inherit' }}></i> Modifier
                            </a>
                        </span>
                    }
                </div>
            </div>
        </div>
    </div>;
})

const CommentForm = React.memo(({blog = null, user, onComment, comment = null, onCancel = null}) => {
    //Variables
    const ref = useRef(null)

    const onSuccess = useCallback(comment => {
        onComment(comment)
        if (comment){

        }else{
            ref.current.value = ''
        }
    }, [ref, onComment])

    //Hooks
    const method = comment ? 'PUT' : 'POST'
    const url = comment ? comment['@id'] : '/api/commentairesblogs'
    const {load, loading, errors, clearError} = useFetch(url, method, onSuccess)

    //Méthodes
    const onSubmit = useCallback(e => {
        e.preventDefault()
        const getUser = comment ? comment.user['@id'] : '/api/users/' + user
        const getBlog = comment ? comment.blogs['@id'] : '/api/blogs/' +blog
        load({
            message: ref.current.value,
            enabled: false,
            user: getUser,
            blogs: getBlog
        })
    }, [load, ref, blog, user])

    //Effects
    useEffect(() => {
        if (comment && comment.message && ref.current){
            ref.current.value = comment.message
        }
    }, [comment, ref])

    return <form onSubmit={onSubmit}>
        <Field
            name="message"
            help="Les commentaires non conformes à notre code de conduite seront modérés."
            ref={ref}
            required
            minLength={5}
            onChange={clearError.bind(this, 'message')}
            error={errors['message']}
        >{comment === null && 'Votre commentaire'}</Field>
        <button disabled={loading} className="btn btn-success">{comment === null ? 'Poster votre commentaire' : 'Editer le commentaire'}</button>
        &nbsp;&nbsp;
        {onCancel &&
        <button className="btn btn-secondary" onClick={onCancel}>Annuler</button>
        }
    </form>
})

function Title({count}) {
    return <div className="page-separator__text">
        {count} Commentaire{count > 1 ? 's' : ''}
    </div>
}

class CommentsElement extends HTMLElement{

    constructor() {
        super();
        this.observer = null
    }
    connectedCallback() {
        const blog = parseInt(this.dataset.blog, 10);
        const user = parseInt(this.dataset.user, 10) || null;
        const avatar = this.dataset.avatar;
        if (this.observer === null){
            this.observer = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting && entry.target === this){
                        observer.disconnect()
                        render(<Comments blog={blog} user={user} avatar={avatar} />, this)
                    }
                })
            })
        }
        this.observer?.observe(this)
    }

    disconnectedCallback() {
        if (this.observer){
            this.observer?.disconnect()
        }
        unmountComponentAtNode(this)
    }
}

customElements.define('blog-comment', CommentsElement)