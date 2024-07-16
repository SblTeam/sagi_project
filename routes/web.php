<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\dashboard\Analytics;
use App\Http\Controllers\layouts\WithoutMenu;
use App\Http\Controllers\layouts\WithoutNavbar;
use App\Http\Controllers\layouts\Fluid;
use App\Http\Controllers\layouts\Container;
use App\Http\Controllers\layouts\Blank;
use App\Http\Controllers\masters\SupplyMaster;
use App\Http\Controllers\masters\ItemMaster;
use App\Http\Controllers\masters\priceMaster;
use App\Http\Controllers\masters\getitemflag;
use App\Http\Controllers\authentications\LoginBasic;
use App\Http\Controllers\authentications\ForgotPasswordBasic;
use App\Http\Controllers\cards\CardBasic;
use App\Http\Controllers\user_interface\Accordion;
use App\Http\Controllers\user_interface\Alerts;
use App\Http\Controllers\user_interface\Badges;
use App\Http\Controllers\user_interface\Buttons;
use App\Http\Controllers\user_interface\Carousel;
use App\Http\Controllers\user_interface\Collapse;
use App\Http\Controllers\user_interface\Dropdowns;
use App\Http\Controllers\user_interface\Footer;
use App\Http\Controllers\user_interface\ListGroups;
use App\Http\Controllers\user_interface\Modals;
use App\Http\Controllers\user_interface\Navbar;
use App\Http\Controllers\user_interface\Offcanvas;
use App\Http\Controllers\user_interface\PaginationBreadcrumbs;
use App\Http\Controllers\user_interface\Progress;
use App\Http\Controllers\user_interface\Spinners;
use App\Http\Controllers\user_interface\TabsPills;
use App\Http\Controllers\user_interface\Toasts;
use App\Http\Controllers\user_interface\TooltipsPopovers;
use App\Http\Controllers\user_interface\Typography;
use App\Http\Controllers\extended_ui\PerfectScrollbar;
use App\Http\Controllers\extended_ui\TextDivider;
use App\Http\Controllers\icons\Boxicons;
use App\Http\Controllers\form_elements\BasicInput;
use App\Http\Controllers\form_elements\InputGroups;
use App\Http\Controllers\form_layouts\VerticalForm;
use App\Http\Controllers\form_layouts\HorizontalForm;
use App\Http\Controllers\tables\Basic as TablesBasic;


// authentication
Route::get('/auth/login-basic', [LoginBasic::class, 'index'])->name('auth-login-basic');
Route::get('/auth/forgot-password-basic', [ForgotPasswordBasic::class, 'index'])->name('auth-reset-password-basic');
Route::post('/auth/login', [LoginBasic::class, 'login'])->name('auth-login');
Route::post('/auth/change-password', [ForgotPasswordBasic::class, 'changepass'])->name('auth-changepass');
Route::get('/auth/logout', [LoginBasic::class, 'logout'])->name('auth-logout');
Route::middleware(['dynamic.auth'])->group(function () {
// Main Page Route
Route::get('/', [Analytics::class, 'index'])->name('dashboard-analytics');
// layout
Route::get('/layouts/without-menu', [WithoutMenu::class, 'index'])->name('layouts-without-menu');
Route::get('/layouts/without-navbar', [WithoutNavbar::class, 'index'])->name('layouts-without-navbar');
Route::get('/layouts/fluid', [Fluid::class, 'index'])->name('layouts-fluid');
Route::get('/layouts/container', [Container::class, 'index'])->name('layouts-container');
Route::get('/layouts/blank', [Blank::class, 'index'])->name('layouts-blank');
// masters
Route::get('/masters/SupplyMaster', [SupplyMaster::class, 'index'])->name('masters-SupplyMaster');
Route::get('/masters/SupplyMaster/add', [SupplyMaster::class, 'add'])->name('masters-SupplyMaster.add');
Route::post('/masters/SupplyMaster', [SupplyMaster::class, 'store'])->name('masters-SupplyMaster');
Route::get('/masters/SupplyMaster/edit/{id}', [SupplyMaster::class, 'edit'])->name('masters-SupplyMaster.edit');
Route::post('/masters/SupplyMaster/{id}', [SupplyMaster::class, 'update'])->name('masters-SupplyMaster.update');
Route::get('/masters/SupplyMaster/delete/{id}', [SupplyMaster::class, 'destroy'])->name('masters-SupplyMaster.destroy');
Route::get('/masters/SupplyMaster/{id}/{status}', [SupplyMaster::class, 'activate'])->name('masters-SupplyMaster.activate');
Route::get('/masters/ItemMaster', [ItemMaster::class, 'index'])->name('masters-ItemMaster');
Route::get('/masters/ItemMaster/add', [ItemMaster::class, 'add'])->name('masters.ItemMaster.add');
//item import
Route::get('/masters/ItemMaster/import', [ItemMaster::class, 'import'])->name('masters.ItemMaster.import');
Route::post('/masters/ItemMaster/import', [ItemMaster::class, 'saveimport'])->name('items.import');
Route::post('/masters/ItemMaster', [ItemMaster::class, 'store'])->name('masters.ItemMaster.store');
Route::get('/masters/ItemMaster/edit/{id}', [ItemMaster::class, 'edit'])->name('masters-ItemMaster.edit');
Route::get('/masters/ItemMaster/activeinactive/{id}', [ItemMaster::class, 'activeinactive'])->name('masters-ItemMaster.activeinactive');
Route::post('/masters/ItemMaster/{id}', [ItemMaster::class, 'update'])->name('masters-ItemMaster.update');
Route::get('/masters/ItemMaster/{selectedGroup}', [ItemMaster::class, 'get'])->name('get.categories');
Route::get('/masters/ItemMaster/delete/{id}', [ItemMaster::class, 'destroy'])->name('masters-ItemMaster.destroy');
//price master
Route::get('/masters/PriceMaster/add', [priceMaster::class, 'add'])->name('masters.PriceMaster.add');
Route::post('/masters/PriceMaster', [PriceMaster::class, 'store'])->name('masters.PriceMaster.store');
Route::get('/masters/PriceMaster/delete/{incr}/{code}', [PriceMaster::class, 'destroy'])->name('masters-PriceMaster.destroy');
Route::get('/masters/PriceMaster/{incr}/{code}', [PriceMaster::class, 'edit'])->name('masters-PriceMaster.edit');
Route::post('/masters/PriceMaster/{incr}/{code}', [PriceMaster::class, 'update'])->name('masters-PriceMaster.update');
Route::get('/masters/PriceMaster', [PriceMaster::class, 'index'])->name('masters-PriceMaster');
Route::get('/masters/getitemflag', [getitemflag::class, 'fetchitemDetails'])->name('masters.getitemflag');
// cards
Route::get('/cards/basic', [CardBasic::class, 'index'])->name('cards-basic');
// User Interface
Route::get('/ui/accordion', [Accordion::class, 'index'])->name('ui-accordion');
Route::get('/ui/alerts', [Alerts::class, 'index'])->name('ui-alerts');
Route::get('/ui/badges', [Badges::class, 'index'])->name('ui-badges');
Route::get('/ui/buttons', [Buttons::class, 'index'])->name('ui-buttons');
Route::get('/ui/carousel', [Carousel::class, 'index'])->name('ui-carousel');
Route::get('/ui/collapse', [Collapse::class, 'index'])->name('ui-collapse');
Route::get('/ui/dropdowns', [Dropdowns::class, 'index'])->name('ui-dropdowns');
Route::get('/ui/footer', [Footer::class, 'index'])->name('ui-footer');
Route::get('/ui/list-groups', [ListGroups::class, 'index'])->name('ui-list-groups');
Route::get('/ui/modals', [Modals::class, 'index'])->name('ui-modals');
Route::get('/ui/navbar', [Navbar::class, 'index'])->name('ui-navbar');
Route::get('/ui/offcanvas', [Offcanvas::class, 'index'])->name('ui-offcanvas');
Route::get('/ui/pagination-breadcrumbs', [PaginationBreadcrumbs::class, 'index'])->name('ui-pagination-breadcrumbs');
Route::get('/ui/progress', [Progress::class, 'index'])->name('ui-progress');
Route::get('/ui/spinners', [Spinners::class, 'index'])->name('ui-spinners');
Route::get('/ui/tabs-pills', [TabsPills::class, 'index'])->name('ui-tabs-pills');
Route::get('/ui/toasts', [Toasts::class, 'index'])->name('ui-toasts');
Route::get('/ui/tooltips-popovers', [TooltipsPopovers::class, 'index'])->name('ui-tooltips-popovers');
Route::get('/ui/typography', [Typography::class, 'index'])->name('ui-typography');
// extended ui
Route::get('/extended/ui-perfect-scrollbar', [PerfectScrollbar::class, 'index'])->name('extended-ui-perfect-scrollbar');
Route::get('/extended/ui-text-divider', [TextDivider::class, 'index'])->name('extended-ui-text-divider');
// icons
Route::get('/icons/boxicons', [Boxicons::class, 'index'])->name('icons-boxicons');
// form elements
Route::get('/forms/basic-inputs', [BasicInput::class, 'index'])->name('forms-basic-inputs');
Route::get('/forms/input-groups', [InputGroups::class, 'index'])->name('forms-input-groups');
// form layouts
Route::get('/form/layouts-vertical', [VerticalForm::class, 'index'])->name('form-layouts-vertical');
Route::get('/form/layouts-horizontal', [HorizontalForm::class, 'index'])->name('form-layouts-horizontal');
});