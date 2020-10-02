import Home from '../pages/home/Index.vue'

import Major from '../pages/department/major/Major'
import Majorinfo from '../pages/department/major/Majorinfo'

import Member from '../pages/department/member/Member'
import Amember from '../pages/department/member/amember'
import Addteacher from '../pages/department/member/Addteacher'
import Alterteacher from '../pages/department/member/Alterteacher'
import Addstudent from '../pages/department/member/Addstudent'
import Alterstudent from '../pages/department/member/Alterstudent'

import Surveyadd from '../pages/requirement/Surveyadd'
import Surveylist from '../pages/requirement/Surveylist'
import Surveyindex from '../pages/requirement/Surveyindex'
import Surveyview from '../pages/requirement/Surveyview'
import Surveydata from '../pages/requirement/Surveydata'
import Surveyedit from '../pages/requirement/Surveyedit'
import Surveypublish from '../pages/requirement/Surveypublish'
import Templatelist from '../pages/requirement/Templatelist'
import Templateview0 from '../pages/requirement/Templateview0'
import Templateview from '../pages/requirement/Templateview'
import Templateadd from '../pages/requirement/Templateadd'
import Templateedit from '../pages/requirement/Templateedit'
import Datastatistics from '../pages/requirement/Datastatistics'
import Surveyviewnew from '../pages/requirement/Surveyviewnew'

import Tracksurvey from '../pages/tracking/Tracksurvey'
import Scaletemplate from '../pages/practice/Scale/Scaletemplate'
import Scaletemplate10 from '../pages/gengangpractice/Scale/Scaletemplate'
import Demandlist from '../pages/apprenticeship/enroll/Demandlist'
import Demandlistcom from '../pages/apprenticeship/enroll/Demandlistcom'
import Demandedit from '../pages/apprenticeship/enroll/Demandedit'
import Demandview from '../pages/apprenticeship/enroll/Demandview'
import Demandpublish from '../pages/apprenticeship/enroll/Demandpublish'
import Demandstatistics from '../pages/apprenticeship/enroll/Demandstatistics'
import Assign from '../pages/apprenticeship/enroll/Assign'

import System from '../pages/system/Index'

import Teacher from '../pages/practice/Teacher'
import Com from '../pages/practice/Company'
import Teacher10 from '../pages/gengangpractice/Ggteacher'
import Com10 from '../pages/gengangpractice/Ggcompany'
import tutor from '../pages/jianxi/Tutor'
import recruitTutor from '../pages/jianxi/Newrecruit/RecruitTutor'
import trainTutor from '../pages/jianxi/Newtrain/TrainTutor'
import tuOngoing from '../pages/jianxi/Newtrain/TuOngoing'
import tuComplete from '../pages/jianxi/Newtrain/TuComplete'
import TrainTab from '../pages/jianxi/Newtrain/TrainTab'
import tuMap from '../pages/jianxi/Newtrain/TuMap'
import Summary from '../pages/jianxi/Newtrain/jxSummary/summary'
import newSummaryTutor from '../pages/jianxi/Newsummary/NewsummaryTeacher'
import NewsummaryTeacher1 from '../pages/jianxi/Newsummary/NewsummaryTeacher1'
import Enterprice from '../pages/jianxi/Enterprice'
import Recruitenter from '../pages/jianxi/Newrecruit/Recruitenter'
import Map from '../pages/jianxi/Newrecruit/Map'
import AddRecruit from '../pages/jianxi/Newrecruit/AddRecruit'
import Newtrain from '../pages/jianxi/Newtrain/Newtrain'
import Compelete from '../pages/jianxi/Newtrain/Compelete'
import Ongoing from '../pages/jianxi/Newtrain/Ongoing'

// import Com20 from '../pages/jianxi/Company'

import Teacher1 from '../pages/apprenticeship/Teacher'
import Com1 from '../pages/apprenticeship/Company'

import Content from '../pages/home/Content.vue'
import My from '../pages/home/My.vue'
import Login from '../pages/home/Login.vue'
import Company from '../pages/company/company'
import Acompany from '../pages/company/Acompany'
import Addcom from '../pages/company/addcom'
import Altercom from '../pages/company/altercom'
import Altertutor from '../pages/company/Altertutor'
/* 映射 */

// 统计
import Statistics from '../pages/statistics/Index.vue'
import checkIn from '../pages/statistics/checkIn'
import Evaluation from '../pages/statistics/evaluation'
import Graduate from '../pages/statistics/graduate'
import Demand from '../pages/statistics/demand'

import User from '../pages/system/User'
import Power from '../pages/system/Power'

export default [
  {
    path: '/',
    redirect: '/home'
  },

  {
    path: '/department/major/major',
    component: Major
  },
  {
    path: '/department/major/majorinfo',
    component: Majorinfo
  },
  {
    path: '/department/member/member',
    component: Member
  },
  {
    path: '/department/member/amember',
    name: 'amember',
    component: Amember
  },
  {
    path: '/department/member/addteacher',
    name: 'addteacher',
    component: Addteacher
  },
  {
    path: '/department/member/Alterteacher',
    name: 'alterteacher',
    component: Alterteacher
  },
  {
    path: '/department/member/addstudent',
    name: 'addstudent',
    component: Addstudent
  },
  {
    path: '/department/member/Alterstudent',
    name: 'alterstudent',
    component: Alterstudent
  },
  {
    path: '/requirement/surveyadd',
    component: Surveyadd
  },
  {
    path: '/requirement/surveylist',
    component: Surveylist
  },
  {
    path: '/requirement/surveyindex',
    component: Surveyindex
  },
  {
    path: '/requirement/surveyview',
    component: Surveyview
  },
  {
    path: '/requirement/surveyviewnew',
    component: Surveyviewnew
  },
  {
    path: '/requirement/surveydata',
    component: Surveydata
  },
  {
    path: '/requirement/surveyedit',
    component: Surveyedit
  },
  {
    path: '/requirement/surveypublish',
    component: Surveypublish
  },
  {
    path: '/requirement/templatelist',
    component: Templatelist
  },
  {
    path: '/requirement/templateview0',
    component: Templateview0
  },
  {
    path: '/requirement/templateview',
    component: Templateview
  },
  {
    path: '/requirement/templateadd',
    component: Templateadd
  },
  {
    path: '/requirement/templateedit',
    component: Templateedit
  },
  {
    path: '/requirement/datastatistics',
    component: Datastatistics
  },
  {
    path: '/tracking/tracksurvey',
    component: Tracksurvey
  },
  {
    path: '/practice/Scale/scaletemplate',
    component: Scaletemplate
  },
  {
    path: '/gengangpractice/Scale/scaletemplate',
    component: Scaletemplate10
  },
  {
    path: '/apprenticeship/enroll/demandlist',
    component: Demandlist
  },
  {
    path: '/apprenticeship/enroll/demandlistcom',
    component: Demandlistcom
  },
  {
    path: '/apprenticeship/enroll/demandedit',
    component: Demandedit
  },
  {
    path: '/apprenticeship/enroll/demandview',
    component: Demandview
  },
  {
    path: '/apprenticeship/enroll/demandpublish',
    component: Demandpublish
  },
  {
    path: '/apprenticeship/enroll/demandstatistics',
    component: Demandstatistics
  },
  {
    path: '/apprenticeship/enroll/assign',
    component: Assign
  },
  {
    path: '/system',
    component: System
  },
  {
    path: '/home',
    redirect: '/home/index',
    component: Home,
    children: [
      {
        path: 'index',
        component: Content
      },
      {
        path: '/my',
        component: My
      },
      {
        path: 'login',
        component: Login
      }
    ]
  },
  {
    path: '/system/Index',
    component: System
  },
  {
    path: '/system/user',
    component: User
  },
  {
    path: '/system/power',
    component: Power
  },

  {
    path: '/company',
    component: Company
  },
  {
    path: '/company/addcom',
    name: 'addcom',
    component: Addcom
  },
  {
    path: '/company/Acompany',
    name: 'acompany',
    component: Acompany
  },
  {
    path: '/company/altercom',
    name: 'altercom',
    component: Altercom
  },
  {
    path: '/company/Altertutor',
    name: 'Altertutor',
    component: Altertutor
  },
  {
    path: '/practice/Teacher',
    component: Teacher
  },
  {
    path: '/practice/Company',
    component: Com
  },
  {
    path: '/gengangpractice/Ggteacher',
    component: Teacher10
  },
  {
    path: '/gengangpractice/Ggcompany',
    component: Com10
  },
  {
    path: '/jianxi/Tutor',
    component: tutor
  },
  {
    path: '/jianxi/Newrecruit/RecruitTutor',
    component: recruitTutor
  },
  {
    path: '/jianxi/Newtrain/TrainTutor',
    component: trainTutor
  },
  {
    path: 'jianxi/Newtrain/TuOnging',
    component: tuOngoing
  },
  {
    path: 'jianxi/Newtrain/TuComplete',
    component: tuComplete
  },
  {
    path: '/jianxi/Newtrain/TrainTab',
    name: 'TrainTab',
    component: TrainTab
  },
  {
    path: '/jianxi/Newtrain/TuMap',
    component: tuMap
  },
  {
    path: '/jianxi/Newtrain/jxSummary/summary',
    component: Summary
  },
  {
    path: 'jianxi/Newsummary/NewsummaryTeacher',
    component: newSummaryTutor
  },
  {
    path: '/jianxi/Newsummary/NewsummaryTeacher1',
    name: 'NewsummaryTeacher1',
    component: NewsummaryTeacher1
  },
  {
    path: '/jianxi/Enterprice',
    name: 'Enterprice',
    component: Enterprice
  },
  {
    path: '/jianxi/Newrecruit/Recruitenter',
    name: 'Recruitenter',
    component: Recruitenter
  },
  {
    path: '/jianxi/Newrecruit/Map',
    name: 'Map',
    component: Map
  },
  {
    path: '/jianxi/Newrecruit/AddRecruit',
    name: 'AddRecruit',
    component: AddRecruit
  },
  {
    path: '/jianxi/Newtrain/Newtrain',
    name: 'Newtrain',
    component: Newtrain
  },
  {
    path: '/jianxi/Newtrain/Compelete',
    name: 'Compelete',
    component: Compelete
  },
  {
    path: '/jianxi/Newtrain/Ongoing',
    name: 'Ongoing',
    component: Ongoing
  },

  {
    path: '/apprenticeship/Teacher',
    component: Teacher1
  },
  {
    path: '/apprenticeship/Company',
    component: Com1
  },
  {
    path: '/',
    redirect: '/statistics'
  },
  {
    path: '/statistics',
    redirect: '/statistics/Index',
    component: Statistics,
    children: [
      {
        path: 'checkIn',
        component: checkIn
      }
      // ,
      // {
      //   path: 'evaluation',
      //   component: Evaluation
      // }
      // ,
      // {
      //   path: 'graduate',
      //   component: Graduate
      // }
      // ,
      // {
      //   path: 'checkInChart',
      //   component: CheckInChart
      // }
    ]
  },
  {
    path: '/statistics/evaluation',
    name: 'evaluation',
    component: Evaluation
  },
  {
    path: '/statistics/graduate',
    name: 'graduate',
    component: Graduate
  },
  {
    path: '/statistics/demand',
    name: 'demand',
    component: Demand
  }
]
