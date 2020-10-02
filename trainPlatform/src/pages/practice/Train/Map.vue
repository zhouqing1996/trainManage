<template>
  <div>
    <el-dialog title="实习打卡" :visible.sync="mapvisible" @close='closeDialog'>
      <el-form :model="form">
        <el-form-item label="打卡时间" :label-width="formLabelWidth">
          <el-time-select placeholder="起始时间" v-model="form.startTime" :picker-options="{start: '06:00',step: '00:15',end: '20:00'}">
          </el-time-select>
          <el-time-select placeholder="结束时间" v-model="form.endTime" :picker-options="{start: '06:00',step: '00:15',end: '20:00',minTime: form.startTime}">
          </el-time-select>
        </el-form-item>
        <el-form-item label="打卡范围" :label-width="formLabelWidth">
          <el-input v-model="form.scope" style="width: 200px"></el-input>米
        </el-form-item>
      </el-form>
      <div class="amap-page-container" v-if="map == 1">
        <div :style="{width:'100%',height:'300px'}">
          <el-amap vid="amap" :plugin="plugin" class="amap-demo" :center="center">
          </el-amap>
        </div>
        <div class="toolbar">
              <span v-if="loaded">
                location: lng = {{ lng }} lat = {{ lat }} address = {{address}}
              </span>
          <span v-else>正在定位</span>
        </div>
      </div>
      <div slot="footer" class="dialog-footer">
        <el-button @click="visible(0)">取 消</el-button>
        <el-button type="primary" @click="visible(1)">确 定</el-button>
      </div>
    </el-dialog>
  </div>
</template>

<script>
    export default {
      name: 'Map',
      props: ['map', 'rowId'],
      data () {
        const self = this
        return {
          form: {
            startTime: '',
            endTime: '',
            scope: ''
          },
          formLabelWidth: '150px',
          mapvisible: false,
          center: [121.59996, 31.197646],
          lng: 0,
          lat: 0,
          address: '',
          loaded: false,
          plugin: [{
            pName: 'Geolocation',
            events: {
              init (o) {
                // o 是高德地图定位插件实例
                o.getCurrentPosition((status, result) => {
                  console.log(result)
                  if (result && result.position) {
                    self.lng = result.position.lng
                    self.lat = result.position.lat
                    self.address = result.formattedAddress
                    self.center = [self.lng, self.lat]
                    self.loaded = true
                    self.$nextTick()
                  }
                })
              }
              // click (e) {
              //   let { lng, lat } = e.lnglat
              //   self.lng = lng
              //   self.lat = lat
              //
              //   // 这里通过高德 SDK 完成。
              //   var geocoder = new AMap.Geocoder({
              //     radius: 1000,
              //     extensions: 'all'
              //   })
              //   geocoder.getAddress([lng, lat], function (status, result) {
              //     if (status === 'complete' && result.info === 'OK') {
              //       if (result && result.regeocode) {
              //         self.address = result.regeocode.formattedAddress
              //         self.$nextTick()
              //       }
              //     }
              //   })
              // }
            }
          }]
        }
      },
      methods: {
        visible (param) {
          if (param == 1) {
            let that = this
            this.$http.post('/practice/train/editmap', { rowId: this.rowId, form: this.form, lng: this.lng, lat: this.lat, address: this.address }).then(function (res) {
              that.$emit('mapchild', 1)
            })
            console.log(this.rowId, this.form, this.lng, this.lat, this.address)
          } else {
            this.$emit('mapchild', 0)
          }
        },
        closeDialog () {
          this.$emit('mapchild', 0)
        }
      },
      mounted () {
        if (this.map) {
          this.mapvisible = this.map
        }
      }
    }
</script>

<style scoped>

</style>
