<template>
  <!-- LEFT SIDEBAR -->
  <div id="sidebar-nav" class="sidebar">
    <div class="sidebar-scroll">
      <nav>
        <ul class="nav">
          <li v-for="meau in meauList" v-show="meau.isVisit" :key="meau.funcId">
            <template v-if="meau.list.length==1">
              <router-link :to="{ path:meau.list[0].url, query:{ name:meau.list[0].name }}">
                <i v-bind:class=" meau.icon "></i>
                <span>{{ meau.list[0].name }}</span>
              </router-link>
            </template>
            <template v-if="meau.list.length>1">
              <a v-bind:href="'#'+meau.url" data-toggle="collapse" class="collapsed">
                <i v-bind:class=" meau.icon "></i>
                <span>{{ meau.name }}</span>
                <i class="lnr lnr-chevron-right" v-if="meau.list.length!=0"></i>
              </a>
              <div v-bind:id="meau.url" class="collapse ">
                <ul class="nav">
                  <router-link v-for="item in meau.list" v-if="item.isVisit"
                               :to="{ path:item.url, query:{ name:item.name }}" tag="li" :key="item.funcId">
                    <a>
                      {{ item.name }}
                    </a>
                  </router-link>
                </ul>
              </div>
            </template>

          </li>
        </ul>
      </nav>
    </div>
  </div>
</template>

<script>
  export default {
    name: 'Nav',
    data () {
      return {
        meauList: [],
        power: this.$store.getters.showPower
      }
    },
    methods: {
      judgeSub (list, index) {
        for (let i in list) {
          switch (list[i].funcId) {
            case '1001':
              if (this.power != null && (this.power.super == '1')) { this.meauList[index].list[i].isVisit = true } else { this.meauList[index].list[i].isVisit = false }
              break
            case '1002':
              if (this.power != null && (this.power.super == '1')) { this.meauList[index].list[i].isVisit = true } else { this.meauList[index].list[i].isVisit = false }
              break
            case '1003':
              if (this.power != null && (this.power.userkind == '1') && (this.power.super != '1')) { this.meauList[index].list[i].isVisit = true } else { this.meauList[index].list[i].isVisit = false }
              break
            case '1004':
              if (this.power != null && (this.power.userkind == '1') && (this.power.super != '1')) { this.meauList[index].list[i].isVisit = true } else { this.meauList[index].list[i].isVisit = false }
              break

            case '2001':
              if (this.power != null && (this.power.super == '1')) { this.meauList[index].list[i].isVisit = true } else { this.meauList[index].list[i].isVisit = false }
              break
            case '2002':
              if (this.power != null && (this.power.userkind == '2')) { this.meauList[index].list[i].isVisit = true } else { this.meauList[index].list[i].isVisit = false }
              break
            // case '3001':
            //   if (this.power != null && (this.power.super == '1' || (this.power.statistics == '1'&&this.power.super == '2'&&this.power.userkind == '1'))) { this.meauList[index].list[i].isVisit = true } else { this.meauList[index].list[i].isVisit = false }
            //   break
            // case '3002':
            //   if (this.power != null && ((this.power.statistics == '1'&&this.power.super == '2'&&this.power.userkind == '2'))) { this.meauList[index].list[i].isVisit = true } else { this.meauList[index].list[i].isVisit = false }
            //   break
            // case '3003':
            //   if (this.power != null && (this.power.super == '1' ||(this.power.statistics == '1'&&this.power.super == '2'&&this.power.userkind == '1'))) { this.meauList[index].list[i].isVisit = true } else { this.meauList[index].list[i].isVisit = false }
            //   break
            // case '3004':
            //   if (this.power != null && (this.power.super == '1' || (this.power.statistics == '1'&&this.power.super == '2'&&this.power.userkind == '2'))) { this.meauList[index].list[i].isVisit = true } else { this.meauList[index].list[i].isVisit = false }
            //   break

            case '4002':
              if (this.power != null && (this.power.super == '1' || this.power.major == '1')) { this.meauList[index].list[i].isVisit = true } else { this.meauList[index].list[i].isVisit = false }
              break
            case '4003':
              if (this.power != null && (this.power.super == '1' || this.power.company == '1')) { this.meauList[index].list[i].isVisit = true } else { this.meauList[index].list[i].isVisit = false }
              break
            // case '5001':
            //   if (this.power != null && (this.power.super == '1' || this.power.major == '1')) { this.meauList[index].list[i].isVisit = true } else { this.meauList[index].list[i].isVisit = false }
            //   break
            // case '5002':
            //   if (this.power != null && (this.power.super == '1' || this.power.company == '1')) { this.meauList[index].list[i].isVisit = true } else { this.meauList[index].list[i].isVisit = false }
            //   break
            // case '6001':
            //   if (this.power != null && (this.power.super == '1' || this.power.major == '1')) { this.meauList[index].list[i].isVisit = true } else { this.meauList[index].list[i].isVisit = false }
            //   break
            // case '6002':
            //   if (this.power != null && (this.power.super == '1' || this.power.major == '1')) { this.meauList[index].list[i].isVisit = true } else { this.meauList[index].list[i].isVisit = false }
            //   break
            case '7001':
              if (this.power != null && (this.power.super == '1' || this.power.major == '1')) { this.meauList[index].list[i].isVisit = true } else { this.meauList[index].list[i].isVisit = false }
              break

            case '8001':
              if (this.power != null && (this.power.super == '1' || this.power.major == '1')) { this.meauList[index].list[i].isVisit = true } else { this.meauList[index].list[i].isVisit = false }
              break
            case '8002':
              if (this.power != null && (this.power.super == '1' || this.power.major == '1')) { this.meauList[index].list[i].isVisit = true } else { this.meauList[index].list[i].isVisit = false }
              break
            case '9001':
              if (this.power != null && (this.power.super == '1' || this.power.major == '1')) { this.meauList[index].list[i].isVisit = true } else { this.meauList[index].list[i].isVisit = false }
              break
            case '9002':
              if (this.power != null && (this.power.super == '1' || this.power.major == '1')) { this.meauList[index].list[i].isVisit = true } else { this.meauList[index].list[i].isVisit = false }
              break
            default:
              break
          }
        }
      },
      getNavList () {
        this.$http.post('/system/index/nav')
          .then((res) => {
            this.meauList = res.data.data
            console.log(11111111)
            console.log(this.meauList)
            for (let item in this.meauList) {
              switch (this.meauList[item].funcId) {
                case '10':
                  if (this.power != null && (this.power.super == '1' || this.power.major == '1')) { this.meauList[item].isVisit = true } else { this.meauList[item].isVisit = false }
                  this.judgeSub(this.meauList[item].list, item)
                  break

                case '20':
                  if (this.power != null && (this.power.super == '1' || this.power.company == '1')) { this.meauList[item].isVisit = true } else { this.meauList[item].isVisit = false }

                  this.judgeSub(this.meauList[item].list, item)
                  break

                // case '30':
                //   if (this.power != null && (this.power.super == '1' || this.power.statistics == '1')) { this.meauList[item].isVisit = true } else { this.meauList[item].isVisit = false }
                //   this.judgeSub(this.meauList[item].list, item)
                //   break

                case '40':
                  if (this.power != null && (this.power.super == '1' || this.power.practice == '1')) { this.meauList[item].isVisit = true } else { this.meauList[item].isVisit = false }

                  this.judgeSub(this.meauList[item].list, item)
                  break
                // case '50':
                //   if (this.power != null && (this.power.super == '1' || this.power.requirement == '1')) { this.meauList[item].isVisit = true } else { this.meauList[item].isVisit = false }
                //
                //   this.judgeSub(this.meauList[item].list, item)
                //   break
                case '90':
                  if (this.power != null && (this.power.super == '1' || this.power.system == '1')) { this.meauList[item].isVisit = true } else { this.meauList[item].isVisit = false }

                  this.judgeSub(this.meauList[item].list, item)
                  break
                // case '60':
                //   if (this.power != null && (this.power.super == '1' || this.power.tracking == '1')) { this.meauList[item].isVisit = true } else { this.meauList[item].isVisit = false }
                //
                //   this.judgeSub(this.meauList[item].list, item)
                //   break

                case '70':
                  if (this.power != null && (this.power.super == '1' || this.power.system == '1')) { this.meauList[item].isVisit = true } else { this.meauList[item].isVisit = false }

                  this.judgeSub(this.meauList[item].list, item)
                  break

                case '80':
                  if (this.power != null && (this.power.super == '1' || this.power.system == '1')) { this.meauList[item].isVisit = true } else { this.meauList[item].isVisit = false }

                  this.judgeSub(this.meauList[item].list, item)
                  break
                default:
                  break
              }
            }
            // 这里要判断下权限
          }, (err) => {
            console.log(err)
          })
      }
    },
    created () {
      this.getNavList()
    }
  }
</script>

<style scoped>
</style>
