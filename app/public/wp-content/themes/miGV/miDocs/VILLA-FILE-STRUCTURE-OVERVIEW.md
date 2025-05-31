# Villa Community - File Structure & Workflow Overview

## 🏗️ **Project Architecture**

This is a **WordPress site** with a **custom theme** (`miGV`) that includes a comprehensive **community management dashboard**. Here's how everything fits together:

---

## 📁 **Key Directory Structure**

```
villa-community20/
├── app/public/wp-content/
│   ├── themes/miGV/                    # 🎨 YOUR CUSTOM THEME
│   │   ├── theme.json                  # 🎯 DESIGN SYSTEM (colors, fonts, spacing)
│   │   ├── functions.php               # ⚙️ Theme functionality & enqueues
│   │   ├── assets/
│   │   │   ├── css/
│   │   │   │   ├── villa-dashboard.css # 💎 Dashboard styling (uses theme.json)
│   │   │   │   └── blocks.css          # 🧱 Block styling
│   │   │   └── js/
│   │   │       ├── villa-dashboard.js  # ⚡ Dashboard interactivity
│   │   │       └── main.js             # 🔧 General site JS
│   │   └── miDocs/                     # 📚 Documentation
│   │
│   └── mu-plugins/                     # 🔌 MUST-USE PLUGINS (auto-active)
│       ├── villa-frontend-dashboard.php    # 🏠 Main dashboard controller
│       ├── villa-dashboard-properties.php  # 🏘️ Property management
│       ├── villa-dashboard-tickets.php     # 🎫 Support tickets
│       ├── villa-dashboard-additional.php  # 👥 Groups, billing, profile
│       ├── villa-groups-cpt.php            # 🤝 Groups/committees system
│       ├── villa-projects-cpt.php          # 📋 Projects system
│       └── villa-cpt-registration.php      # 📝 Custom post types
```

---

## 🎨 **Design System Workflow**

### **1. theme.json = Design Foundation**
```json
{
  "settings": {
    "color": {
      "palette": [
        {"name": "Primary", "color": "#5a7f80"},      // Teal blue-green
        {"name": "Primary Light", "color": "#8dabac"},
        {"name": "Secondary", "color": "#c6d3ce"},
        // ... all your brand colors
      ]
    }
  }
}
```

### **2. CSS Uses Theme Variables**
```css
.stat-card {
    background: var(--wp--preset--color--primary);     /* Uses theme.json */
    color: var(--wp--preset--color--base-white);
}
```

### **3. Benefits of This System**
- ✅ **Consistent branding** across entire site
- ✅ **Easy color changes** - edit theme.json, everything updates
- ✅ **WordPress native** - works with Gutenberg editor
- ✅ **Future-proof** - follows WordPress standards

---

## 🏠 **Dashboard System Architecture**

### **Main Controller**
- **`villa-frontend-dashboard.php`** = The "brain" that:
  - Handles the `[villa_dashboard]` shortcode
  - Routes to different tabs (Properties, Projects, Groups, etc.)
  - Manages user permissions and roles
  - Includes all the module files

### **Individual Modules**
Each dashboard section is a separate file:

| File | Purpose | What It Does |
|------|---------|--------------|
| `villa-dashboard-properties.php` | 🏘️ Property Management | List/edit properties, handle ownership |
| `villa-dashboard-tickets.php` | 🎫 Support System | Work orders, maintenance requests |
| `villa-dashboard-additional.php` | 👥 Groups & More | Committees, billing, profile management |
| `villa-groups-cpt.php` | 🤝 Groups System | Committee structure, membership |
| `villa-projects-cpt.php` | 📋 Projects System | Roadmap, committee work, surveys |

### **How It All Connects**
1. **User visits** `/dashboard` page
2. **WordPress loads** the `[villa_dashboard]` shortcode
3. **Main controller** (`villa-frontend-dashboard.php`) takes over
4. **Based on URL** (`?tab=projects`), it loads the right module
5. **Module renders** the content using your theme colors
6. **JavaScript** (`villa-dashboard.js`) adds interactivity

---

## 🔧 **WordPress Integration Points**

### **Must-Use Plugins (mu-plugins)**
- **Auto-active** - can't be deactivated
- **Perfect for** core functionality like your dashboard
- **Loads before** regular plugins and themes

### **Custom Post Types (CPTs)**
- **`property`** - Individual units/homes
- **`villa_projects`** - Community projects and roadmap
- **`villa_groups`** - Committees and staff groups
- **`support_ticket`** - Maintenance and support requests

### **User Roles & Permissions**
- **Owner** - Can manage their properties, view announcements
- **BOD** (Board of Directors) - Full access to committees and projects
- **Staff** - Access based on their role (maintenance, office, etc.)
- **Committee Members** - Access to their specific committees

---

## 🎯 **Current Status & What We Just Fixed**

### **✅ Recently Completed**
1. **Fixed Groups page** - No more "Array to string" errors
2. **Enhanced Projects dashboard** - Working filters, beautiful cards
3. **Removed unnecessary buttons** - Cleaner property interface
4. **Added comprehensive styling** - All using your theme.json colors
5. **Created interactive JavaScript** - Filter tabs now work

### **🏗️ System Architecture**
- **Foundation Complete** ✅ Properties, Users, Roles, Basic Dashboard
- **Core Features Working** ✅ Groups, Projects, Tickets, Styling
- **Ready for Enhancement** 🚀 Advanced features, integrations

---

## 🚀 **Development Workflow**

### **Making Changes**
1. **Design changes** → Edit `theme.json`
2. **Styling changes** → Edit `villa-dashboard.css` (uses theme variables)
3. **Functionality changes** → Edit the relevant module file
4. **New features** → Create new files in `mu-plugins/`

### **Testing Changes**
1. **Local development** - Changes show immediately
2. **Check multiple user roles** - Owner vs BOD vs Staff
3. **Test on mobile** - Responsive design included

### **Git Workflow**
```bash
git add .
git commit -m "Description of changes"
git push origin main    # Pushes to GitHub
```

---

## 🎓 **Key Concepts to Remember**

### **1. Separation of Concerns**
- **theme.json** = Design system
- **CSS files** = Visual styling
- **PHP files** = Functionality and data
- **JS files** = User interactions

### **2. WordPress Native Approach**
- Uses **shortcodes** instead of custom page templates
- Leverages **WordPress user roles** and permissions
- Follows **WordPress coding standards**

### **3. Scalable Architecture**
- **Modular design** - each feature in its own file
- **Consistent patterns** - all modules follow same structure
- **Theme integration** - everything uses your design system

---

## 🔮 **Next Steps & Possibilities**

### **Ready to Enhance**
- 📧 **Email notifications** for tickets and announcements
- 📱 **Mobile app integration** via REST API
- 🔗 **Third-party integrations** (payment, communication)
- 📊 **Analytics and reporting** dashboards
- 🗳️ **Voting systems** for community decisions

### **Easy to Extend**
- Add new dashboard tabs by creating new module files
- Extend existing features by editing the relevant module
- Change colors/fonts by updating theme.json
- Add new user roles through WordPress admin

---

This architecture gives you a **solid foundation** that's both **WordPress-native** and **highly customizable**. You can grow it in any direction while maintaining consistency and performance!
