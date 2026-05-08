import "./modal.scss"

export default (initialState = false) => ({
    isOpen: initialState,
    init() {
        this.$watch('isOpen', (isOpen) => {
            if (isOpen) {
                document.body.style.paddingRight = `${window.innerWidth - document.body.clientWidth}px`
                document.documentElement.style.overflow = 'hidden'
            }
            else {
                document.body.style.paddingRight = 0
                document.documentElement.style.overflow = 'auto'
            }
        })
    },
    close() {
        this.isOpen = false
    },
    open() {
        this.isOpen = true
    },
    root: {
        ['x-ref']: 'root',
        ['x-transition:enter']: 'modal--enter',
        ['x-transition:enter-start']: 'modal--enter-start',
        ['x-transition:enter-end']: 'modal--enter-end',
        ['x-transition:leave']: 'modal--leave',
        ['x-transition:leave-start']: 'modal--leave-start',
        ['x-transition:leave-end']: 'modal--leave-end',
        ['x-modelable']: 'isOpen',
        ['x-show']: 'isOpen',
        ['@keyup.escape.window']: 'close()',
    },
    overlay: {
        ['@click.self']: 'close()'
    },
    hide: {
        ['@click']: 'close()'
    }
})
