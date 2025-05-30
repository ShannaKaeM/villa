# GutenVibes + Villa Community Integration
**AI-Powered Live Editor Project Setup**

---

## 🎯 **Project Overview**

This document tracks the integration of Daniel's GutenVibes AI-powered live editor system with the Villa Community WordPress project. For complete technical architecture details, see `/gvibes-stack`.

### **Core Vision**
- Click on any component → Fields appear in left sidebar
- Fill out fields → Live preview updates instantly  
- Switch between mobile/tablet/desktop breakpoints
- AI agents generate and modify content in real-time

---

## 📋 **Development Phases**

### **Phase 1: Foundation Alignment** ✅ *COMPLETE*
- [x] CMB2 blocks with AI-ready data attributes
- [x] JSON-friendly field structure
- [x] Live-target CSS selectors prepared
- [x] Block categorization and metadata

### **Phase 2: Live Editor Core** 🔄 *AWAITING DANIEL*
- [ ] React-based sidebar interface
- [ ] Real-time field updates via WebSocket/PostMessage
- [ ] Responsive breakpoint switcher
- [ ] Click-to-edit overlay system

### **Phase 3: AI Integration** 📅 *PLANNED*
- [ ] AI agent communication API
- [ ] Content generation endpoints
- [ ] Natural language field updates
- [ ] Automated component suggestions

### **Phase 4: Multi-site Management** 📅 *FUTURE*
- [ ] Independent web app interface
- [ ] Cross-site content management
- [ ] Centralized AI command center
- [ ] Bulk editing capabilities

---

## 🔧 **Current Villa File Structure**

```
villa-community20/
├── app/public/wp-content/
│   ├── mu-plugins/
│   │   ├── villa-cmb2-blocks.php          ✅ AI-ready blocks
│   │   ├── villa-frontend-dashboard.php    ✅ User interface
│   │   ├── villa-dashboard-post-types.php  ✅ CPT definitions
│   │   ├── villa-live-editor.php          🔄 Live editor prototype
│   │   └── assets/
│   │       ├── villa-live-editor.js       📅 React components (Daniel)
│   │       └── villa-live-editor.css      📅 Live editor styles (Daniel)
│   └── themes/miGV/
│       ├── templates/blocks/              ✅ Twig templates
│       └── miDocs/                        📁 This documentation
```

---

## 📊 **Villa Progress Tracking**

### **Completed Features** ✅
- **Property Showcase Block** - AI-ready with data attributes
- **Dashboard Layout Block** - AI-ready with data attributes
- **Properties Management Block** - AI-ready with data attributes
- **Support Tickets Block** - AI-ready with data attributes
- **Business Management Block** - AI-ready with data attributes
- **User Profile Management** - Role-based access system
- **Multi-owner Property System** - Ownership tracking via meta fields

### **Current Villa Stack** 
- **WordPress Core** - CMS foundation
- **CMB2** - Custom fields (ready for Daniel's enhancement)
- **Timber/Twig** - Template engine
- **Custom Post Types** - Properties, Tickets, Businesses, Users

### **Integration Points for Daniel**
1. **CMB2 Enhancement** - Replace meta boxes with React sidebar
2. **Template Integration** - Use existing Twig templates for live preview
3. **Data Structure** - Leverage existing JSON-friendly field setup
4. **Component System** - Convert existing blocks to live-editable components

---

## 🎨 **Villa Field Standards**

### **AI-Ready Attributes** (Already Implemented)
Every CMB2 field includes:
```php
'attributes' => array(
    'data-ai-field' => 'field_purpose',      // AI identification
    'data-live-target' => '.css-selector',   // Live editing target
    'data-json-key' => 'object.property'     // JSON structure mapping
),
```

### **Block Classification** (Already Implemented)
```php
'classes' => 'villa-ai-ready-block',
'data' => array(
    'block-type' => 'component-name',
    'ai-editable' => 'true',
    'live-preview' => 'true'
),
```

---

## 🚀 **Current Workflow**

### **Shanna's Tasks** (Ongoing)
1. **Content Structure** - Continue building CMB2 blocks following AI-ready patterns
2. **Template Creation** - Twig templates with live-target classes
3. **Data Architecture** - Ensure JSON-friendly field relationships
4. **Villa Features** - Dashboard, properties, user management

### **Daniel's Integration Points** (When Ready)
1. **React Conversion** - Convert CMB2 meta boxes to React sidebar
2. **Live Preview Engine** - Real-time field → DOM updates using existing Twig templates
3. **AI API Layer** - Agent communication and content generation
4. **Component System** - Web Components for reusable elements

---

## 📝 **Key Decisions & Notes**

### **Why This Approach Works**
- **Villa foundation is AI-ready** - All fields have proper data attributes
- **Minimal changes needed** - Daniel's system will enhance, not replace
- **Existing templates work** - Twig templates already have live-target selectors
- **JSON-friendly structure** - Data is already API-ready

### **Integration Benefits**
- **Seamless transition** - From meta boxes to live editor
- **Preserved functionality** - All Villa features remain intact
- **Enhanced UX** - Live editing without losing existing workflows
- **AI-powered content** - Automatic generation for all Villa content types

---

## 🔗 **Related Documentation**

- **`/gvibes-stack`** - Complete technical architecture and development workflow
- **`/villa-portol`** - Villa Community project overview and features
- **Villa Development Tracker** - Detailed feature progress and implementation notes

---

**Status:** Foundation Complete - Ready for Daniel's Live Editor Integration  
**Last Updated:** May 30, 2025  
**Next Steps:** Await Daniel's CMB2 enhancement and React sidebar implementation
