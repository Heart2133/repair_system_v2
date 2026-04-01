<!-- ========== Left Sidebar Start ========== -->
<div class="vertical-menu" style="background-color: #d4dbf9 ;">

    <div data-simplebar class="h-100">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title" key="t-menu">@lang('translation.Menu')</li>
                @if(Auth::user()->name != "xxxx" and Auth::user()->name != "pc")
                <li>
                    <a href="{{ route('/') }}" class="waves-effect">
                        <i class="bx bx-home-circle"></i>
                        <span key="t-chat">dashboards</span>
                    </a>
                </li>
                @if(Auth::user()->type == "vendor" || Auth::user()->type == "admin" || Auth::user()->type == "finance")
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-receipt"></i>
                        <span key="t-payment">Payment</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('billing-list') }}"><span class="badge rounded-pill text-bg-success float-end" style="display:none" key="t-new">@lang('translation.New')</span> <span key="t-billing-list">รายการรับวางบิล</span></a></li>
                        <li><a href="{{ route('payment-list') }}" key="t-payment-list">รายการชำระเงิน</a></li>
                    </ul>
                </li>
                @endif
                <!--
                <li>
                    <a href="javascript: void(0);" class="waves-effect" data-bs-toggle="modal" data-bs-target=".change-password">
                        <i class="bx bx-wrench"></i>
                        <span key="t-chat">เปลี่ยนรหัสผ่าน</span>
                    </a>
                </li>
-->
                @if(Auth::user()->type == "admin" || Auth::user()->type == "finance" || Auth::user()->type == "po")
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bxs-user-detail"></i>
                        <span key="t-payment">Admin</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        @if(Auth::user()->type == "admin" || Auth::user()->type == "po")
                        <li><a href="{{ route('purchasing-import') }}" key="t-purchasing-import">สำหรับฝ่ายจัดซื้อ</a></li>
                        @endif
                        @if(Auth::user()->type == "admin" || Auth::user()->type == "finance")
                        <li><a href="{{ route('finance-import') }}" key="t-finance-import">สำหรับฝ่ายการเงิน</a></li>
                        @endif
                        @if(Auth::user()->type == "admin" || Auth::user()->type == "finance")
                        <li><a href="{{ route('users') }}" key="t-vendor-setting">จัดการผู้ใช้</a></li>
                        <li><a href="{{ route('notices') }}" key="t-notice-setting">จัดการประกาศสำคัญ</a></li>
                        @endif
                    </ul>
                </li>
                @endif

                <li>
                    <a href="help" class="waves-effect">
                        <i class="bx bx-help-circle"></i>
                        <span key="t-help">ช่วยเหลือ</span>
                    </a>
                </li>

                <li class="menu-title" key="t-apps">Report</li>

                @if(Auth::user()->type == "vendor")
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bxs-report"></i>
                        <span key="t-purchasing-report">รายงานการวางบิล</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('finance-confirm-report') }}" key="t-rejected-report">รายงานใบวางบิล</a></li>
                    </ul>
                </li>

                @endif

                @if(Auth::user()->type == "admin" || Auth::user()->type == "po")
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bxs-report"></i>
                        <span key="t-purchasing-report">รายงานฝ่ายจัดซื้อ</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('rejected-report') }}" key="t-rejected-report">Rejected Items Report</a></li>
                        <li><a href="{{ route('confirmed-po-report') }}" key="t-confirmed-po-report">Confirmed PO Report</a></li>
                        <li><a href="{{ route('pending-po-report') }}" key="t-pending-po-report">Pending PO Report</a></li>
                    </ul>
                </li>
                @endif

                @if(Auth::user()->type == "admin" || Auth::user()->type == "finance")
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bxs-report"></i>
                        <span key="t-finance-report">รายงานฝ่ายการเงิน</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{route('finance-report')}}" key="t-rejected-report">รายงานใบวางบิล</a></li>
                        <li><a href="{{route('finance-month-report')}}" key="t-finance-month-report">รายงานสรุปใบวางบิล เดือน/ปี</a></li>
                    </ul>
                </li>
                @endif
                @endif
                @if(Auth::user()->name == "xxxx" or  Auth::user()->name == "pc")

                <li class="menu-title" key="t-apps">ChatBot</li>

                <li>
                    <a href="{{ route('chatbot-manage-user') }}" class="waves-effect">
                        <i class="bx bx-cog"></i>
                        <span key="t-chat">Manage User Chatbot</span>
                    </a>
                </li>
                @endif
                @if(Auth::user()->name == "xxxx" )
                <li>
                    <a href="{{ route('chatbot-manage') }}" class="waves-effect">
                        <i class="bx bx-cog"></i>
                        <span key="t-chat">Manage Chatbot</span>
                    </a>
                </li>
                @endif
                @if(Auth::user()->name == "xxxx")
                <li>
                    <a href="{{ route('chatbot-log') }}" class="waves-effect">
                        <i class="bx bx-cog"></i>
                        <span key="t-chat">Chatbot Log</span>
                    </a>
                </li>
                @endif
                @if(Auth::user()->name == "xxxx" or Auth::user()->name == "pc" or Auth::user()->name == "fi")
                <li>
                    <a href="{{ route('vendors') }}" class="waves-effect">
                        <i class="bx bx-cog"></i>
                        <span key="t-chat">Vendor</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('customer') }}" class="waves-effect">
                        <i class="bx bx-cog"></i>
                        <span key="t-chat">Customer</span>
                    </a>
                </li>
                @endif

            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
<!-- Left Sidebar End -->