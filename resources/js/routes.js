import Home from './components/Home';
import Account from './components/Account';
import NewHome from './components/NewHome';
import NewHomeTwo from './components/NewHomeTwo';
import About from './components/About';
import Register from './components/Register';
import Login from './components/Login';
import NotFound from './components/NotFound';
import Alliance from './components/Alliance';
import EditAlliance from './components/EditAlliance';
import CreateAlliance from './components/CreateAlliance';
import Section from './components/Section';
import EditSection from './components/EditSection';
import CreateSection from './components/CreateSection';
import Category from './components/Category';
import ShowSection from './components/ShowSection';
import ShowCategory from './components/ShowCategory';
import CreateCategory from './components/CreateCategory';
import EditCategory from './components/EditCategory';
import Content from './components/Content';
import CreateContent from './components/CreateContent';
import Poll from './components/Poll';
import CreatePoll from './components/CreatePoll';
import User from './components/User';
import CreateUser from './components/CreateUser';
import EditUser from './components/EditUser';
import ShowContent from './components/ShowContent';
import EditContent from './components/EditContent';
import Audit from './components/Audit';
import Metric from './components/Metric';
import ShowPoll from './components/ShowPoll';
import EditPoll from './components/EditPoll';
import MetricResult from './components/MetricResult';
import PollResult from './components/PollResult';
import ShowSectionContent from './components/ShowSectionContent';
import Qr from './components/Qr';
import ShowSpecialSection from './components/ShowSpecialSection';
import ShowInformation from './components/ShowInformation';
import Rol from './components/Rol';
import CreateRol from './components/CreateRol';
import EditRol from './components/EditRol';

export default{
    mode: 'history',
    linkActiveClass: 'font-semibold',
    routes: [
        {
            path: '*',
            component: NotFound
        },
        {
            path: '/',
            component: Home,
            name: "Home"
        },
        {
            path: '/account',
            name: 'account',
            component: Account
        },
        {
            path: '/about',
            component: About
        },
        {
            path: '/register',
            component: Register
        },
        {
            path: '/login',
            component: Login,
            name: 'Login'
        },
        {
            path: '/newhome',
            component: NewHome,
            name: 'NewHome'
        },
        {
            path: '/newhometwo',
            component: NewHomeTwo,
            name: 'NewHomeTwo'
        },
        {
          path: '/alliance',
          name: 'alliance',
          component: Alliance
        },
        {
          path: '/alliance/edit/:id',
          name: 'editalliance',
          component: EditAlliance
        },
        {
          path: '/alliance/create',
          name: 'createalliance',
          component: CreateAlliance
        },
        {
          path: '/section',
          name: 'section',
          component: Section
        },
        {
          path: '/section/show/:id',
          name: 'showsection',
          component: ShowSection
        },
        {
          path: '/section/edit/:id',
          name: 'editsection',
          component: EditSection
        },
        {
          path: '/section/create',
          name: 'createsection',
          component: CreateSection
        },
        {
          path: '/category/show/:id',
          name: 'showcategory',
          component: ShowCategory
        },
        {
          path: '/category',
          name: 'category',
          component: Category
        },
        {
          path: '/category/create',
          name: 'createcategory',
          component: CreateCategory
        },
        {
          path: '/category/edit/:id',
          name: 'editsection',
          component: EditCategory
        },
        {
          path: '/content',
          name: 'content',
          component: Content
        },
        {
          path: '/content/create',
          name: 'createcontent',
          component: CreateContent
        },
        {
          path: '/poll',
          name: 'poll',
          component: Poll
        },
        {
          path: '/poll/create',
          name: 'createpoll',
          component: CreatePoll
        },
        {
          path: '/user',
          name: 'user',
          component: User
        },
        {
          path: '/user/create',
          name: 'createuser',
          component: CreateUser
        },
        {
          path: '/user/edit/:id',
          name: 'edituser',
          component: EditUser
        },
        {
          path: '/content/show/:id',
          name: 'showcontent',
          component: ShowContent
        },
        {
          path: '/content/edit/:id',
          name: 'editcontent',
          component: EditContent
        },
        {
          path: '/audit',
          name: 'audit',
          component: Audit
        },
        {
          path: '/metric',
          name: 'metric',
          component: Metric
        },
        {
          path: '/poll/show/:id',
          name: 'showpoll',
          component: ShowPoll
        },
        {
          path: '/poll/edit/:id',
          name: 'editpoll',
          component: EditPoll
        },
        {
          path: '/metric/result/:id',
          name: 'metricresult',
          component: MetricResult
        },
        {
          path: '/poll/result/:id',
          name: 'pollresult',
          component: PollResult
        },
        {
          path: '/section/content/show/:id',
          name: 'show_section_content',
          component: ShowSectionContent
        },
        {
          path: '/qr',
          name: 'qr',
          component: Qr
        },
        {
          path: '/special_section/show/:id',
          name: 'show_special_section',
          component: ShowSpecialSection
        },
        {
          path: '/information/show/:id',
          name: 'show_information',
          component: ShowInformation
        },
        {
          path: '/rol',
          name: 'rol',
          component: Rol
        },
        {
          path: '/rol/create',
          name: 'create_rol',
          component: CreateRol
        },
        {
          path: '/rol/edit/:id',
          name: 'edit_rol',
          component: EditRol
        }
    ]
}