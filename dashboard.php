<?php
session_start();
    if (!isset($_SESSION['user_id'])) {
        header("Location: index.php");
        exit;
    }

$role = $_SESSION['role'];
//print_r($role);
include('./header.php');

if($role==3){

  $sql='SELECT project.project_name, project.status project_employees.employee_id from project, project_employee where project.id = project_employee. ,'
    ?>
      <!-- Content -->
      <div class="container-xxl flex-grow-1 container-p-y">
          <!-- Hour chart  -->
          <div class="card bg-transparent shadow-none border-0 mb-6">
            <div class="card-body row g-6 p-0 pb-5">
              <div class="col-12 col-md-8 card-separator">
                <h5 class="mb-2">Welcome Back,<br>Here's Your Dashboard</h5>
                <div class="col-12 col-lg-5">
                      <p>Your progress this week is Awesome. let's keep it up!!</p>
                </div>
                <div class="d-flex justify-content-between flex-wrap gap-4 me-12">
                  <div class="d-flex align-items-center gap-4 me-6 me-sm-0">
                  <div class="avatar avatar-lg">
                      <div class="avatar-initial bg-label-warning rounded-3">
                        <div>
                          <img src="assets/svg/icons/check.svg" alt="Check" class="img-fluid" />
                        </div>
                      </div>
                    </div>
                    <div class="content-right">
                      <p class="mb-1 fw-medium">completed Projects </p>
                      <span class="text-primary mb-0 h5">34h</span>
                    </div>
                  </div>
                  <div class="d-flex align-items-center gap-4">
                    <div class="avatar avatar-lg">
                      <div class="avatar-initial bg-label-info rounded-3">
                        <div>
                          <img src="assets/svg/icons/lightbulb.svg" alt="Lightbulb" class="img-fluid" />
                        </div>
                      </div>
                    </div>
                    <div class="content-right">
                      <p class="mb-1 fw-medium">Due Projects</p>
                      <span class="text-info mb-0 h5">82%</span>
                    </div>
                  </div>
                  <div class="d-flex align-items-center gap-4">
                  <div class="avatar avatar-lg">
                      <div class="avatar-initial bg-label-primary rounded-3">
                        <div>
                          <img src="assets/svg/icons/laptop.svg" alt="paypal" class="img-fluid" />
                        </div>
                      </div>
                    </div>
                    <div class="content-right">
                      <p class="mb-1 fw-medium">Course Completed</p>
                      <span class="text-warning mb-0 h5">14</span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-12 col-md-4 ps-md-4 ps-lg-6">
                <div class="d-flex justify-content-between align-items-center">
                  <div>
                    <div>
                      <h5 class="mb-1">Time Spendings</h5>
                      <p class="mb-9">Weekly report</p>
                    </div>
                    <div class="time-spending-chart">
                      <h5 class="mb-2">231<span class="text-body">h</span> 14<span class="text-body">m</span></h5>
                      <span class="badge bg-label-success rounded-pill">+18.4%</span>
                    </div>
                  </div>
                  <div id="leadsReportChart"></div>
                </div>
              </div>
            </div>
          </div>
          <!-- Hour chart End  -->

          <!-- Topic and Instructors -->
          <div class="row mb-6 g-6">

            <!-- Popular Instructors -->
            <div class="col-md-6 col-xxl-4">
              <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                  <div class="card-title mb-0">
                    <h5 class="m-0 me-2">Completed Projects</h5>
                  </div>
                  
                </div>
                
                <div class="card-body pt-5">
                  
                </div>
              </div>
            </div>
            <!--/ Popular Instructors -->

            <!-- Top Courses -->
            <div class="col-12 col-xxl-4 col-md-6">
              <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                  <h5 class="card-title m-0 me-2">Due Projects</h5>
                </div>
                <div class="card-body">
                  
                </div>
              </div>
            </div>
            <!--/ Top Courses -->

            <!-- Upcoming Webinar -->
            <div class="col-12 col-xxl-4 col-md-6">
              <div class="card h-80">
                <div class="card-body">
                  <div class="bg-label-primary text-center mb-6 pt-2 rounded-3">
                    <img
                      class="img-fluid w-px-150"
                      src="assets/img/illustrations/faq-illustration.png"
                      alt="Boy card image" />
                  </div>
                  <h5 class="mb-1">Join Date</h5>
                  <div class="row mb-6 g-4">
                    <div class="col-6">
                      <div class="d-flex">
                        <div class="avatar flex-shrink-0 me-4">
                          <span class="avatar-initial rounded-3 bg-label-primary"
                            ><i class="ri-calendar-line ri-24px"></i
                          ></span>
                        </div>
                        <div>
                          <h6 class="mb-0 text-nowrap fw-normal">17 Nov 23</h6>
                          <small>Date</small>
                        </div>
                      </div>
                    </div>
                    <div class="col-6">
                      <div class="d-flex">
                        <div class="avatar flex-shrink-0 me-4">
                          <span class="avatar-initial rounded-3 bg-label-primary"
                            ><i class="ri-time-line ri-24px"></i
                          ></span>
                        </div>
                        <div>
                          <h6 class="mb-0 text-nowrap fw-normal">8 Hours</h6>
                          <small>Office Hours</small>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!--/ Upcoming Webinar -->

            <!-- Assignment Progress -->
            <div class="col-12 col-xxl-4 col-md-6">
              <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                  <h5 class="card-title m-0 me-2">Salary Status</h5>
                </div>
                <div class="card-body pt-5">
                  
                </div>
              </div>
            </div>
            <!--/ Assignment Progress -->
          </div>
          <!--  Topic and Instructors  End-->

          <!-- Assignment Progress -->
          <div>
              <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                  <h5 class="card-title m-0 me-2">Leave Status</h5>
                </div>
                <div class="card-body pt-5">
                  
                </div>
              </div>
            </div>
            <!--/ Assignment Progress -->
      </div>
      <!--/ Content -->
  <?php
}
?>
<?php include("footer.php"); ?>