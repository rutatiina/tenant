
const TenantsForm = () => import('./components/l-limitless-bs4/Form.vue')
const TenantsIndex = () => import('./components/l-limitless-bs4/Index.vue')

const routes = [
    {
        path: '/settings/organisations',
        components: {
            default: TenantsIndex
        },
        meta: {
            title: 'Organisations',
            metaTags: [
                {
                    name: 'description',
                    content: 'Organisations'
                },
                {
                    property: 'og:description',
                    content: 'Organisations'
                }
            ]
        }
    },
    {
        path: '/settings/organisations/create',
        components: {
            default: TenantsForm,
            //'sidebar-left': ComponentSidebarLeft,
            //'sidebar-right': ComponentSidebarRight
        },
        meta: {
            title: 'Organisations :: Create',
            metaTags: [
                {
                    name: 'description',
                    content: 'Create an Organisations'
                },
                {
                    property: 'og:description',
                    content: 'Create an Organisations'
                }
            ]
        }
    },
    {
        path: '/settings/organisations/:id/edit',
        components: {
            default: TenantsForm,
        },
        meta: {
            title: 'Organisations :: Edit',
            metaTags: [
                {
                    name: 'description',
                    content: 'Edit Organisations'
                },
                {
                    property: 'og:description',
                    content: 'Edit Organisations'
                }
            ]
        }
    },
]

export default routes
