/**
 * 侧边栏导航功能
 */

// 等待DOM加载完成
document.addEventListener('DOMContentLoaded', function() {
    initSidebar();
});

/**
 * 初始化侧边栏
 */
function initSidebar() {
    // 获取侧边栏元素
    const sidebar = document.querySelector('.sidebar');
    const toggleBtn = document.querySelector('.toggle-btn');
    const menuLinks = document.querySelectorAll('.menu-link[data-submenu]');
    
    // 从localStorage获取侧边栏状态
    const sidebarCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
    if (sidebarCollapsed) {
        sidebar.classList.add('collapsed');
    }
    
    // 切换侧边栏展开/收起
    if (toggleBtn) {
        toggleBtn.addEventListener('click', function() {
            sidebar.classList.toggle('collapsed');
            const isCollapsed = sidebar.classList.contains('collapsed');
            localStorage.setItem('sidebarCollapsed', isCollapsed);
            
            // 如果收起侧边栏，关闭所有子菜单
            if (isCollapsed) {
                closeAllSubmenus();
            }
        });
    }
    
    // 一级菜单点击事件（展开/收起二级菜单）
    menuLinks.forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            // 如果侧边栏是收起状态，不处理子菜单展开
            if (sidebar.classList.contains('collapsed')) {
                return;
            }
            
            const submenuId = this.getAttribute('data-submenu');
            const submenu = document.getElementById(submenuId);
            const isExpanded = this.classList.contains('expanded');
            
            // 关闭其他所有子菜单
            menuLinks.forEach(function(otherLink) {
                if (otherLink !== link) {
                    otherLink.classList.remove('expanded');
                    const otherSubmenuId = otherLink.getAttribute('data-submenu');
                    const otherSubmenu = document.getElementById(otherSubmenuId);
                    if (otherSubmenu) {
                        otherSubmenu.classList.remove('show');
                    }
                }
            });
            
            // 切换当前子菜单
            if (isExpanded) {
                this.classList.remove('expanded');
                submenu.classList.remove('show');
            } else {
                this.classList.add('expanded');
                submenu.classList.add('show');
            }
            
            // 保存展开状态到localStorage
            saveMenuState();
        });
    });
    
    // 二级菜单点击事件
    const submenuLinks = document.querySelectorAll('.submenu-link');
    submenuLinks.forEach(function(link) {
        link.addEventListener('click', function() {
            // 移除所有二级菜单的active状态
            submenuLinks.forEach(function(otherLink) {
                otherLink.classList.remove('active');
            });
            
            // 添加当前链接的active状态
            this.classList.add('active');
            
            // 保存当前激活的菜单
            localStorage.setItem('activeSubmenu', this.getAttribute('href'));
        });
    });
    
    // 恢复菜单状态
    restoreMenuState();
    
    // 高亮当前页面对应的菜单项
    highlightCurrentPage();
    
    // 移动端处理
    setupMobileMenu();
}

/**
 * 关闭所有子菜单
 */
function closeAllSubmenus() {
    const menuLinks = document.querySelectorAll('.menu-link[data-submenu]');
    menuLinks.forEach(function(link) {
        link.classList.remove('expanded');
        const submenuId = link.getAttribute('data-submenu');
        const submenu = document.getElementById(submenuId);
        if (submenu) {
            submenu.classList.remove('show');
        }
    });
}

/**
 * 保存菜单状态
 */
function saveMenuState() {
    const expandedMenus = [];
    const menuLinks = document.querySelectorAll('.menu-link.expanded[data-submenu]');
    menuLinks.forEach(function(link) {
        expandedMenus.push(link.getAttribute('data-submenu'));
    });
    localStorage.setItem('expandedMenus', JSON.stringify(expandedMenus));
}

/**
 * 恢复菜单状态
 */
function restoreMenuState() {
    const sidebar = document.querySelector('.sidebar');
    
    // 如果侧边栏是收起状态，不恢复菜单展开状态
    if (sidebar.classList.contains('collapsed')) {
        return;
    }
    
    // 恢复展开的菜单
    const expandedMenus = JSON.parse(localStorage.getItem('expandedMenus') || '[]');
    expandedMenus.forEach(function(submenuId) {
        const menuLink = document.querySelector('.menu-link[data-submenu="' + submenuId + '"]');
        const submenu = document.getElementById(submenuId);
        if (menuLink && submenu) {
            menuLink.classList.add('expanded');
            submenu.classList.add('show');
        }
    });
    
    // 恢复激活的二级菜单
    const activeSubmenu = localStorage.getItem('activeSubmenu');
    if (activeSubmenu) {
        const activeLink = document.querySelector('.submenu-link[href="' + activeSubmenu + '"]');
        if (activeLink) {
            activeLink.classList.add('active');
        }
    }
}

/**
 * 高亮当前页面对应的菜单项
 */
function highlightCurrentPage() {
    const currentPath = window.location.pathname;
    const submenuLinks = document.querySelectorAll('.submenu-link');
    
    submenuLinks.forEach(function(link) {
        const href = link.getAttribute('href');
        if (href && currentPath.includes(href)) {
            // 激活当前链接
            link.classList.add('active');
            
            // 展开父级菜单
            const parentSubmenu = link.closest('.submenu');
            if (parentSubmenu) {
                parentSubmenu.classList.add('show');
                const parentMenuLink = document.querySelector('.menu-link[data-submenu="' + parentSubmenu.id + '"]');
                if (parentMenuLink) {
                    parentMenuLink.classList.add('expanded');
                }
            }
        }
    });
}

/**
 * 移动端菜单设置
 */
function setupMobileMenu() {
    // 创建遮罩层
    const overlay = document.createElement('div');
    overlay.className = 'sidebar-overlay';
    document.body.appendChild(overlay);
    
    const sidebar = document.querySelector('.sidebar');
    
    // 点击遮罩层关闭侧边栏
    overlay.addEventListener('click', function() {
        sidebar.classList.remove('mobile-open');
        overlay.classList.remove('show');
    });
    
    // 可以添加一个移动端菜单按钮
    // 这里留给具体页面实现
}

/**
 * 打开移动端侧边栏（供外部调用）
 */
function openMobileSidebar() {
    const sidebar = document.querySelector('.sidebar');
    const overlay = document.querySelector('.sidebar-overlay');
    
    if (sidebar && overlay) {
        sidebar.classList.add('mobile-open');
        overlay.classList.add('show');
    }
}

/**
 * 关闭移动端侧边栏（供外部调用）
 */
function closeMobileSidebar() {
    const sidebar = document.querySelector('.sidebar');
    const overlay = document.querySelector('.sidebar-overlay');
    
    if (sidebar && overlay) {
        sidebar.classList.remove('mobile-open');
        overlay.classList.remove('show');
    }
}

// 导出函数供外部使用
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        openMobileSidebar: openMobileSidebar,
        closeMobileSidebar: closeMobileSidebar
    };
}

