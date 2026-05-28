export default (time = '400ms') => ({
    open: false,
    root: {
        ['@click.outside']: 'open = false',
        ['x-modelable']: 'open'
    },
    toggle: {
        ['@click']: 'open = !open'
    },
    collapse: {
        [`x-collapse.duration.${time}`]: '',
        ['x-show']: 'open'
    }
})
