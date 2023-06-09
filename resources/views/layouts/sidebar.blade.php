<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="/backend/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p>Alexander Pierce</p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>

      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
          <li class="active">
              <a href="">
                  <i class="fa fa-list"></i> <span>QUẢN LÝ USER </span>
              </a>
          </li>
          <li class="active">
              <a href="{{route('list-partner')}}">
                  <i class="fa fa-list"></i> <span>QUẢN LÝ ĐỐI TÁC </span>
              </a>
          </li>

        <li class="active">

          <a href="{{route('list-category')}}">
            <i class="fa fa-list"></i> <span>QUẢN LÝ DANH MỤC SẢN PHẨM</span>
          </a>
        </li>
          <li class="active">
              <a href="{{route('list')}}">
                  <i class="fa fa-list"></i> <span>QUẢN LÝ SẢN PHẨM </span>
              </a>
          </li>
          <li class="active">
              <a href="{{route('list-product-original')}}">
                  <i class="fa fa-list"></i> <span>QUẢN LÝ SẢN PHẨM GỐC</span>
              </a>
          </li>
          <li class="active">
              <a href="{{route('list-product-partner')}}">
                  <i class="fa fa-list"></i> <span>QUẢN LÝ SẢN PHẨM ĐỐI TÁC</span>
              </a>
          </li>
          <li class="active">
              <a href="">
                  <i class="fa fa-list"></i> <span>LỊCH SỬ</span>
              </a>
          </li>
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>
