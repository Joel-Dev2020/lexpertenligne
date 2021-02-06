import React from 'react'

const classNameOption = (...arr) => arr.filter(Boolean).join(' ')

export const Field = React.forwardRef(({help, name, children, error, onChange, required, minLength}, ref) => {
    if (error) {
        help = error
    }
    return <div className="form-group">
        <label htmlFor={name} className="form-label">{children}</label>
        <textarea
            className={classNameOption('form-control', error && 'is-invalid')}
            ref={ref}
            placeholder="Laisser un commentaire"
            name={name}
            id={name}
            required={required}
            minLength={minLength}
            onChange={onChange}
            rows="5"></textarea>
        {help && <span className={classNameOption(error && 'invalid-feedback')}>{help}</span>}
    </div>
})