<?php
    use App\Helpers\Utility;
    $util = new Utility();
?>
@extends('layouts.master')
@section('title', 'AWS')
@section('content') 
<div class="container">
    <div class="app-content main-content">
        <div class="side-app">        
            <!--Page header-->
            <div class="page-header d-xl-flex d-block">
                <div class="page-leftheader">
                    <h4 class="page-title">Board Notice</h4>
                </div>
                <div class="page-rightheader ml-md-auto">
                    <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                        <div class="btn-list">
                            <a href="/notices" class="btn btn-primary mr-3">Upcomming List</a>
                            <a href="/notices/dlist" class="btn btn-primary mr-3">Archive List</a>
                            <a href="/notices/deletedList" class="btn btn-danger mr-3">Deleted List</a>
                            <a href="/notices/create" class="btn btn-primary mr-3">Add Board Notice</a>
                        </div>
                    </div>
                </div>
            </div>

            <!--End Page header-->
            <!-- Row -->
            <div class="row">
                <div class="col-xl-12 col-md-10 col-lg-10">
                    <div class="card">
                        <div class="card-header  border-0">
                            <h4 class="card-title">Board Notice Details</h4>
                        </div>
                        <div class="card-body">
                          <div class="row">
                              <div class="col-md-3">
                                  <div class="form-group">
                                      <label class="form-label">Select Branch:</label>
                                      {{Form::select('branchId', $branches, $notice->branchId, ['placeholder'=>'Select Branch','class'=>'branchId form-control', 'id'=>'branchId', 'disabled'])}}
                                  </div>
                              </div>  
                              <div class="col-md-3">
                                  <div class="form-group">
                                      <label class="form-label">Select Departments:</label>.
                                      <select class="form-control" name="" multiple="multiple" disabled>
                                        <option value="">Select Department</option>
                                          <?php $selectedDepartmentId = explode(',', $notice->departmentId);?>
                                          @foreach($departments as $department)
                                            <option value="{{$department->id}}" <?php echo (in_array($department->id, $selectedDepartmentId))?'selected':''; ?> >{{$department->name}}</option> 
                                          @endforeach                                        
                                      </select>
                                  </div>
                              </div> 
                              <div class="col-md-3">
                                  <div class="form-group">
                                      <label class="form-label">From Date<span class="text-red">*</span>:</label>
                                      <input type="date" class="form-control" name="fromDate" value="{{$notice->fromDate}}" placeholder="" disabled>
                                  </div>
                              </div>  
                              <div class="col-md-3">
                                  <div class="form-group">
                                      <label class="form-label">To Date<span class="text-red">*</span>:</label>
                                      <input type="date" class="form-control" name="toDate" value="{{$notice->toDate}}" placeholder="" disabled>
                                  </div>
                              </div> 
                          </div> 
                          <div class="row"> 
                              <div class="col-md-3">
                                  <div class="form-group">
                                      <label class="form-label">Title<span class="text-red">*</span>:</label>
                                      <input type="text" class="form-control" name="title" value="{{$notice->title}}" placeholder="Title" disabled>
                                  </div>
                              </div>     
                              <div class="col-md-12">
                                  <div class="form-group">
                                      <label class="form-label">Notice<span class="text-red">*</span>:</label>
                                      <textarea class="form-control" name="description" placeholder="Notice Board" cols="10" disabled>{{$notice->description}}</textarea>
                                  </div>
                              </div>                                  
                          </div> 
                          <div class="row"> 
                              <div class="col-md-3">
                                  <div class="form-group">
                                      <label class="form-label">Added At<span class="text-red">*</span>:</label>
                                      <input type="text" class="form-control" name="title" value="{{date('d-m-Y H:i', strtotime($notice->created_at))}}" placeholder="Title" disabled>
                                  </div>
                              </div>  
                              <div class="col-md-3">
                                  <div class="form-group">
                                      <label class="form-label">Added By<span class="text-red">*</span>:</label>
                                      <input type="text" class="form-control" name="title" value="{{$notice->added_by}}" placeholder="Title" disabled>
                                  </div>
                              </div>      
                              <div class="col-md-3">
                                  <div class="form-group">
                                      <label class="form-label">Updated at<span class="text-red">*</span>:</label>
                                      <input type="text" class="form-control" name="title" value="{{date('d-m-Y H:i', strtotime($notice->updated_at))}}" placeholder="Title" disabled>
                                  </div>
                              </div>
                              <div class="col-md-3">
                                  <div class="form-group">
                                      <label class="form-label">Updated By<span class="text-red">*</span>:</label>
                                      <input type="text" class="form-control" name="title" value="{{$notice->updated_by}}" placeholder="Title" disabled>
                                  </div>
                              </div>          
                          </div> 
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Row-->
        </div>
    </div><!-- end app-content-->
</div>
@endsection
<script>
    // helper functions
const PI2 = Math.PI * 2
const random = (min, max) => Math.random() * (max - min + 1) + min | 0
const timestamp = _ => new Date().getTime()

// container
class Birthday {
  constructor() {
    this.resize()

    // create a lovely place to store the firework
    this.fireworks = []
    this.counter = 0

  }
  
  resize() {
    this.width = canvas.width = window.innerWidth
    let center = this.width / 2 | 0
    this.spawnA = center - center / 4 | 0
    this.spawnB = center + center / 4 | 0
    
    this.height = canvas.height = window.innerHeight
    this.spawnC = this.height * .1
    this.spawnD = this.height * .5
    
  }
  
  onClick(evt) {
     let x = evt.clientX || evt.touches && evt.touches[0].pageX
     let y = evt.clientY || evt.touches && evt.touches[0].pageY
     
     let count = random(3,5)
     for(let i = 0; i < count; i++) this.fireworks.push(new Firework(
        random(this.spawnA, this.spawnB),
        this.height,
        x,
        y,
        random(0, 260),
        random(30, 110)))
          
     this.counter = -1
     
  }
  
  update(delta) {
    ctx.globalCompositeOperation = 'hard-light'
    ctx.fillStyle = `rgba(20,20,20,${ 7 * delta })`
    ctx.fillRect(0, 0, this.width, this.height)

    ctx.globalCompositeOperation = 'lighter'
    for (let firework of this.fireworks) firework.update(delta)

    // if enough time passed... create new new firework
    this.counter += delta * 3 // each second
    if (this.counter >= 1) {
      this.fireworks.push(new Firework(
        random(this.spawnA, this.spawnB),
        this.height,
        random(0, this.width),
        random(this.spawnC, this.spawnD),
        random(0, 360),
        random(30, 110)))
      this.counter = 0
    }

    // remove the dead fireworks
    if (this.fireworks.length > 1000) this.fireworks = this.fireworks.filter(firework => !firework.dead)

  }
}

class Firework {
  constructor(x, y, targetX, targetY, shade, offsprings) {
    this.dead = false
    this.offsprings = offsprings

    this.x = x
    this.y = y
    this.targetX = targetX
    this.targetY = targetY

    this.shade = shade
    this.history = []
  }
  update(delta) {
    if (this.dead) return

    let xDiff = this.targetX - this.x
    let yDiff = this.targetY - this.y
    if (Math.abs(xDiff) > 3 || Math.abs(yDiff) > 3) { // is still moving
      this.x += xDiff * 2 * delta
      this.y += yDiff * 2 * delta

      this.history.push({
        x: this.x,
        y: this.y
      })

      if (this.history.length > 20) this.history.shift()

    } else {
      if (this.offsprings && !this.madeChilds) {
        
        let babies = this.offsprings / 2
        for (let i = 0; i < babies; i++) {
          let targetX = this.x + this.offsprings * Math.cos(PI2 * i / babies) | 0
          let targetY = this.y + this.offsprings * Math.sin(PI2 * i / babies) | 0

          birthday.fireworks.push(new Firework(this.x, this.y, targetX, targetY, this.shade, 0))

        }

      }
      this.madeChilds = true
      this.history.shift()
    }
    
    if (this.history.length === 0) this.dead = true
    else if (this.offsprings) { 
        for (let i = 0; this.history.length > i; i++) {
          let point = this.history[i]
          ctx.beginPath()
          ctx.fillStyle = 'hsl(' + this.shade + ',100%,' + i + '%)'
          ctx.arc(point.x, point.y, 1, 0, PI2, false)
          ctx.fill()
        } 
      } else {
      ctx.beginPath()
      ctx.fillStyle = 'hsl(' + this.shade + ',100%,50%)'
      ctx.arc(this.x, this.y, 1, 0, PI2, false)
      ctx.fill()
    }

  }
}

let canvas = document.getElementById('birthday')
let ctx = canvas.getContext('2d')

let then = timestamp()

let birthday = new Birthday
window.onresize = () => birthday.resize()
document.onclick = evt => birthday.onClick(evt)
document.ontouchstart = evt => birthday.onClick(evt)

  ;(function loop(){
  	requestAnimationFrame(loop)

  	let now = timestamp()
  	let delta = now - then

    then = now
    birthday.update(delta / 1000)
  	

  })()
</script>
