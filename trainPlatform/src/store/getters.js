
export default {
  getAccessToken:(state)=>{
    return state.access_token
  },
  getUser:(state)=>{
    return state.user
  },
  getUserName:(state)=>{
    return state.name
  },
  getUserKind:(state)=>{
    return state.kind
  },
  getMeetId:(state)=>{
    return state.meetId
  },
  showSider:(state)=>{
    return state.sider
  },
  showByLogin:(state)=>{
    return state.login
  },
  loading:(state)=>{
    return state.Loading
  },
  showHeader:(state)=>{
    return state.header
  },
  showFooter:(state)=>{
    return state.footer
  },
  showSmart:(state)=>{
    return state.Smart
  },
  showPower:(state)=>{
    return state.power
  },
  showModule:(state)=>{
    return state.module
  },
}
