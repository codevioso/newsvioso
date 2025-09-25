// Import pages
import Login from '../pages/auth/Login.vue';
import ForgotPassword from '../pages/auth/ForgotPassword.vue';
import ResetPassword from '../pages/auth/ResetPassword.vue';
import Dashboard from '../pages/Dashboard.vue';
import Profile from '../pages/profile/Profile.vue';
import ProfileUpdate from '../pages/profile/ProfileUpdate.vue';
import ChangePassword from '../pages/profile/ChangePassword.vue';
import AdminList from '../pages/admins/AdminList.vue';
import AdminCreate from '../pages/admins/AdminCreate.vue';
import AdminEdit from '../pages/admins/AdminEdit.vue';
import CategoryList from '../pages/settings/CategoryList.vue';
import CategoryCreate from '../pages/settings/CategoryCreate.vue';
import CategoryEdit from '../pages/settings/CategoryEdit.vue';
import TagList from '../pages/settings/TagList.vue';
import TagCreate from '../pages/settings/TagCreate.vue';
import TagEdit from '../pages/settings/TagEdit.vue';
import ArticleList from '../pages/articles/ArticleList.vue';
import ArticleCreate from '../pages/articles/ArticleCreate.vue';
import ArticleEdit from '../pages/articles/ArticleEdit.vue';
import ArticlePreview from '../pages/articles/ArticlePreview.vue';
import ActionButtonsDemo from '../pages/articles/ActionButtonsDemo.vue';

const routes = [
    {
        path: '/secure/administrator',
        redirect: '/secure/administrator/dashboard'
    },
    {
        path: '/secure/administrator/login',
        name: 'login',
        component: Login,
        meta: { requiresGuest: true }
    },
    {
        path: '/secure/administrator/forgot-password',
        name: 'forgot-password',
        component: ForgotPassword,
        meta: { requiresGuest: true }
    },
    {
        path: '/secure/administrator/reset-password',
        name: 'reset-password',
        component: ResetPassword,
        meta: { requiresGuest: true }
    },
    {
        path: '/secure/administrator/dashboard',
        name: 'dashboard',
        component: Dashboard,
        meta: { requiresAuth: true }
    },
    {
        path: '/secure/administrator/profile',
        name: 'profile',
        component: Profile,
        meta: { requiresAuth: true }
    },
    {
        path: '/secure/administrator/profile/update',
        name: 'profile-update',
        component: ProfileUpdate,
        meta: { requiresAuth: true }
    },
    {
        path: '/secure/administrator/profile/change/password',
        name: 'change-password',
        component: ChangePassword,
        meta: { requiresAuth: true }
    },
    {
        path: '/secure/administrator/admins',
        name: 'admin-list',
        component: AdminList,
        meta: { requiresAuth: true, requiresRole: 'super_admin' }
    },
    {
        path: '/secure/administrator/admins/create',
        name: 'admin-create',
        component: AdminCreate,
        meta: { requiresAuth: true, requiresRole: 'super_admin' }
    },
    {
        path: '/secure/administrator/admins/:id/edit',
        name: 'admin-edit',
        component: AdminEdit,
        meta: { requiresAuth: true, requiresRole: 'super_admin' }
    },
    // Settings Routes (Categories)
    {
        path: '/secure/administrator/settings/categories',
        name: 'category-list',
        component: CategoryList,
        meta: { requiresAuth: true, requiresRole: 'super_admin,editor' }
    },
    {
        path: '/secure/administrator/settings/categories/create',
        name: 'category-create',
        component: CategoryCreate,
        meta: { requiresAuth: true, requiresRole: 'super_admin,editor' }
    },
    {
        path: '/secure/administrator/settings/categories/:id/edit',
        name: 'category-edit',
        component: CategoryEdit,
        meta: { requiresAuth: true, requiresRole: 'super_admin,editor' }
    },
    // Settings Routes (Tags)
    {
        path: '/secure/administrator/settings/tags',
        name: 'tag-list',
        component: TagList,
        meta: { requiresAuth: true, requiresRole: 'super_admin,editor' }
    },
    {
        path: '/secure/administrator/settings/tags/create',
        name: 'tag-create',
        component: TagCreate,
        meta: { requiresAuth: true, requiresRole: 'super_admin,editor' }
    },
    {
        path: '/secure/administrator/settings/tags/:id/edit',
        name: 'tag-edit',
        component: TagEdit,
        meta: { requiresAuth: true, requiresRole: 'super_admin,editor' }
    },
    // Article Routes
    {
        path: '/secure/administrator/articles',
        name: 'article-list',
        component: ArticleList,
        meta: { requiresAuth: true, requiresRole: 'super_admin,editor,reporter' }
    },
    {
        path: '/secure/administrator/articles/create',
        name: 'article-create',
        component: ArticleCreate,
        meta: { requiresAuth: true, requiresRole: 'super_admin,editor,reporter' }
    },
    {
        path: '/secure/administrator/articles/:id/edit',
        name: 'article-edit',
        component: ArticleEdit,
        meta: { requiresAuth: true, requiresRole: 'super_admin,editor,reporter' }
    },
    {
        path: '/secure/administrator/articles/:id/preview',
        name: 'article-preview',
        component: ArticlePreview,
        meta: { requiresAuth: true, requiresRole: 'super_admin,editor,reporter' }
    },
    // Demo route for testing action buttons
    {
        path: '/secure/administrator/demo/action-buttons',
        name: 'action-buttons-demo',
        component: ActionButtonsDemo,
        meta: { requiresAuth: true, requiresRole: 'super_admin,editor,reporter' }
    }
];

export default routes;
