(() => {
    const el = window.wp.element.createElement;
    const { registerBlockType } = window.wp.blocks;

    registerBlockType('gpc-block/gpc-dummy', {
        title: 'GPC Dummy',
        icon: 'universal-access-alt',
        category: 'layout',
        attributes: {
            content: {
                type: 'array',
                source: 'children',
                selector: 'p',
            },
        },
        edit: myEdit,
    });

    // what's rendered in admin
    function myEdit(props) {

        return el('div', {
            className: props.className,
        }, 'Silence is golden!');
    }
})();