# AI-Powered Live Editor Project Setup
**Villa Community WordPress Plugin Development**

---

## 🎯 **Project Vision**
Building an AI-powered WordPress plugin that enables live visual editing with real-time preview, responsive breakpoint switching, and AI agent integration.

### **Core Concept**
- Click on any component (hero, cards, etc.) → Fields appear in left sidebar
- Fill out fields → Live preview updates instantly
- Switch between mobile/tablet/desktop breakpoints
- AI agents can generate and modify content in real-time

---

## 🏗️ **Technical Architecture**

### **Current Foundation (Shanna's Setup)**
- ✅ **CMB2 Field System** - AI-ready with data attributes
- ✅ **Custom Post Types** - Properties, Tickets, Businesses, Users
- ✅ **Twig Templates** - Timber-based rendering
- ✅ **JSON-Friendly Structure** - Ready for API integration

### **Target Architecture (Daniel's Vision)**
- **CMB2 Fork/Extension** - Enhanced with modern UI
- **React Components** - Dynamic field rendering
- **Web Components** - Reusable UI elements
- **Live Preview Engine** - Real-time updates
- **AI Integration Layer** - Agent communication
- **Multi-site Management** - Command center approach

---

## 📋 **Development Phases**

### **Phase 1: Foundation Alignment** ✅ *COMPLETE*
- [x] CMB2 blocks with AI-ready data attributes
- [x] JSON-friendly field structure
- [x] Live-target CSS selectors prepared
- [x] Block categorization and metadata

### **Phase 2: Live Editor Core** 🔄 *IN PROGRESS*
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

## 🔧 **Current File Structure**

```
villa-community20/
├── app/public/wp-content/
│   ├── mu-plugins/
│   │   ├── villa-cmb2-blocks.php          ✅ AI-ready blocks
│   │   ├── villa-frontend-dashboard.php    ✅ User interface
│   │   ├── villa-dashboard-post-types.php  ✅ CPT definitions
│   │   ├── villa-live-editor.php          🔄 Live editor prototype
│   │   └── assets/
│   │       ├── villa-live-editor.js       📅 React components
│   │       └── villa-live-editor.css      📅 Live editor styles
│   └── themes/miGV/
│       ├── templates/blocks/              ✅ Twig templates
│       └── miDocs/                        📁 This documentation
```

---

## 🎨 **Field Structure Standards**

### **AI-Ready Attributes**
Every CMB2 field includes:
```php
'attributes' => array(
    'data-ai-field' => 'field_purpose',      // AI identification
    'data-live-target' => '.css-selector',   // Live editing target
    'data-json-key' => 'object.property'     // JSON structure mapping
),
```

### **Block Classification**
```php
'classes' => 'villa-ai-ready-block',
'data' => array(
    'block-type' => 'component-name',
    'ai-editable' => 'true',
    'live-preview' => 'true'
),
```

---

## 🚀 **Development Workflow**

### **Shanna's Current Tasks**
1. **Content Structure** - Build out CMB2 blocks following AI-ready patterns
2. **Template Creation** - Twig templates with live-target classes
3. **Data Architecture** - Ensure JSON-friendly field relationships
4. **Testing Foundation** - Verify all blocks work as meta boxes

### **Daniel's Integration Points**
1. **React Conversion** - Convert CMB2 meta boxes to React sidebar
2. **Live Preview Engine** - Real-time field → DOM updates
3. **AI API Layer** - Agent communication and content generation
4. **Component System** - Web Components for reusable elements

---

## 📊 **Progress Tracking**

### **Completed Features**
- ✅ Property Showcase Block (AI-ready)
- ✅ Dashboard Layout Block (AI-ready)
- ✅ Properties Management Block (AI-ready)
- ✅ Support Tickets Block (AI-ready)
- ✅ Business Management Block (AI-ready)
- ✅ Custom Post Types (Properties, Tickets, Businesses)
- ✅ User Dashboard Interface
- ✅ Twig Template Structure

### **Next Priorities**
- 🔄 Live Editor Prototype Testing
- 📅 React Sidebar Component
- 📅 Real-time Preview System
- 📅 AI Agent Integration

---

## 🎯 **Success Metrics**

### **Technical Goals**
- **Zero Code Rewrite** - Seamless transition from meta boxes to live editor
- **AI-Friendly** - LLMs can easily understand and modify field structure
- **Performance** - Live updates under 100ms
- **Responsive** - Works across all breakpoints

### **User Experience Goals**
- **Intuitive** - Click-to-edit workflow
- **Visual** - Live preview of all changes
- **Fast** - No page reloads required
- **Powerful** - AI-assisted content creation

---

## 🔗 **Key Dependencies**

### **Current Stack**
- **WordPress** - Core platform
- **CMB2** - Field management system
- **Timber/Twig** - Template engine
- **Custom Post Types** - Data structure

### **Future Stack**
- **React** - Dynamic UI components
- **Web Components** - Reusable elements
- **WebSocket/PostMessage** - Real-time communication
- **AI APIs** - Content generation and assistance

---

## 📝 **Notes & Decisions**

### **Why CMB2 Over ACF?**
- Simpler syntax for AI understanding
- Less complex than Carbon Fields
- Better LLM training data availability
- Easier to extend and customize

### **Why React + Web Components?**
- Modern, maintainable codebase
- Reusable component library
- Easy AI integration
- Future-proof architecture

### **Why JSON-First Approach?**
- AI-friendly data structure
- Easy import/export
- API-ready for multi-site management
- Scalable for complex content types

---

## 🚨 **Important Reminders**

1. **Keep CMB2 Syntax** - Don't change field structure unnecessarily
2. **Add Data Attributes** - Every field needs AI-ready attributes
3. **CSS Target Classes** - Templates need live-editing selectors
4. **JSON Structure** - Think about data relationships for AI
5. **Component Thinking** - Build reusable, modular pieces

---

**Last Updated:** May 30, 2025  
**Next Review:** When Daniel begins live editor integration  
**Contact:** Shanna & Daniel for architecture decisions
