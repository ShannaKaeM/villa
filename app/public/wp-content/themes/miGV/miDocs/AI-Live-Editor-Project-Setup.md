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

## 📁 **AI-Powered Live Editor Tech Stack & Workflow**

### **Data Layer (Foundation)**
```
CMB2 Fields (Enhanced) → JSON API → React UI → Live Preview
```

#### **CMB2 Enhanced System**
- **Custom CMB2 Field Types** - Daniel's page builder field extension
- **JSON-Friendly Structure** - All data serializable for AI/API
- **Data Attributes** - Live-editing target selectors
- **Field Metadata** - AI generation hints and validation

#### **Storage & Retrieval**
- **WordPress Meta Tables** - Primary data storage
- **REST API Endpoints** - Real-time data access
- **WebSocket/PostMessage** - Live updates
- **Version Control** - Change tracking for AI learning

### **Presentation Layer**
```
Twig Templates → Web Components → Live DOM → AI Targets
```

#### **Template System**
- **Timber/Twig** - Server-side rendering
- **Component Templates** - Reusable UI blocks
- **Data Binding** - CMB2 field → Template variable mapping
- **CSS Custom Properties** - Theme integration

#### **Frontend Components**
- **Web Components** - Encapsulated UI elements
- **CSS Grid/Flexbox** - Responsive layouts
- **Theme Integration** - Uses theme.json variables
- **Progressive Enhancement** - Works without JS

### **Interface Layer**
```
Click Detection → Sidebar UI → Field Updates → Live Preview
```

#### **Live Editor Interface**
- **React Sidebar** - Dynamic field rendering
- **Click Overlay System** - Component selection
- **Responsive Switcher** - Breakpoint testing
- **Real-time Updates** - No page refresh needed

#### **AI Integration**
- **Agent Communication** - API endpoints for AI
- **Content Generation** - Text, images, layouts
- **Context Awareness** - Site data, user preferences
- **Learning System** - Improves over time

---

## 🛠️ **Development Workflow**

### **1. Component Creation Process**

#### **Step 1: Define CMB2 Fields**
```php
// Enhanced CMB2 with Daniel's page builder field
$cmb->add_field(array(
    'name' => 'Hero Section',
    'id' => 'hero_content',
    'type' => 'page_builder', // Daniel's custom field
    'ai_hints' => array(
        'generate' => 'hero_content',
        'context' => 'business_landing'
    ),
    'live_target' => '[data-component="hero"]'
));
```

#### **Step 2: Create Twig Template**
```twig
{# hero-section.twig #}
<section class="hero" data-component="hero" data-cmb2-field="hero_content">
    <h1 data-field="title">{{ hero.title }}</h1>
    <p data-field="subtitle">{{ hero.subtitle }}</p>
    <a href="{{ hero.cta_url }}" data-field="cta">{{ hero.cta_text }}</a>
</section>
```

#### **Step 3: Register Component**
```php
// Auto-registration via CMB2 enhanced system
villa_register_component('hero_section', array(
    'template' => 'hero-section.twig',
    'fields' => 'hero_content',
    'category' => 'layout',
    'ai_enabled' => true
));
```

### **2. Live Editing Workflow**

#### **User Interaction Flow**
1. **Click Component** → Overlay highlights, sidebar opens
2. **Edit Fields** → React form renders CMB2 fields
3. **Real-time Update** → Changes apply instantly via DOM manipulation
4. **Save Changes** → Data persists to WordPress meta tables

#### **AI Generation Flow**
1. **Context Analysis** → AI reads site data, user intent
2. **Content Generation** → AI creates field values
3. **Live Preview** → Generated content appears instantly
4. **User Refinement** → Human can edit AI suggestions

### **3. Responsive Design Workflow**

#### **Breakpoint System**
```css
/* CSS Custom Properties for responsive design */
:root {
    --mobile-max: 768px;
    --tablet-max: 1024px;
    --desktop-min: 1025px;
}

[data-component] {
    /* Base styles */
}

@media (max-width: var(--mobile-max)) {
    [data-component] {
        /* Mobile overrides */
    }
}
```

#### **Live Responsive Testing**
- **Breakpoint Switcher** - Toggle between device sizes
- **Real-time Resize** - See changes across all breakpoints
- **Device Simulation** - Accurate mobile/tablet preview

---

## 🔧 **Technical Stack**

### **Backend (WordPress)**
- **WordPress Core** - CMS foundation
- **CMB2 Enhanced** - Daniel's page builder extension
- **Timber/Twig** - Template engine
- **Custom Post Types** - Content structure
- **REST API** - Data endpoints
- **WebSocket Support** - Real-time communication

### **Frontend (Live Editor)**
- **React 18+** - UI framework
- **Web Components** - Reusable elements
- **CSS Custom Properties** - Theme integration
- **PostMessage API** - Cross-frame communication
- **Service Workers** - Offline capabilities

### **AI Integration**
- **OpenAI API** - Content generation
- **Custom AI Agents** - Specialized tasks
- **Context Processors** - Site data analysis
- **Learning Algorithms** - Improvement over time

### **Development Tools**
- **Composer** - PHP dependency management
- **npm/Yarn** - JavaScript packages
- **Webpack/Vite** - Asset bundling
- **Git** - Version control
- **Local by Flywheel** - Development environment

---

## 📁 **File Structure**

```
wp-content/
├── themes/your-theme/
│   ├── templates/           # Twig templates
│   ├── assets/             # CSS, JS, images
│   └── theme.json          # Theme configuration
├── mu-plugins/
│   ├── ai-live-editor/     # Main plugin
│   │   ├── core/           # CMB2 enhancements
│   │   ├── components/     # Component definitions
│   │   ├── api/           # REST endpoints
│   │   └── ai/            # AI integration
│   └── project-specific/   # Custom functionality
└── uploads/ai-cache/       # AI-generated content cache
```

---

## 🚀 **Implementation Strategy**

### **Phase 1: Foundation (Current)**
- ✅ CMB2 field definitions with data attributes
- ✅ Twig template system
- ✅ Component registration system
- ✅ Theme integration

### **Phase 2: Live Editor Core**
- React sidebar interface
- Click-to-edit overlay system
- Real-time field updates
- Responsive breakpoint switcher

### **Phase 3: AI Integration**
- AI agent communication layer
- Content generation endpoints
- Context-aware suggestions
- Learning and improvement system

### **Phase 4: Advanced Features**
- Multi-site management
- Version control and rollback
- Performance optimization
- Plugin marketplace integration

---

## 🎯 **Key Principles**

### **AI-First Design**
- Every field has AI generation capability
- Context-aware content suggestions
- Learning from user preferences
- Seamless human-AI collaboration

### **Developer Experience**
- Minimal code approach
- JSON-friendly architecture
- Familiar CMB2 syntax
- Easy component creation

### **User Experience**
- Live visual editing
- No page refresh needed
- Intuitive click-to-edit
- Responsive design testing

### **Performance**
- Lazy loading components
- Efficient DOM updates
- Cached AI responses
- Optimized asset delivery

---

This architecture provides a solid foundation for Daniel's vision while maintaining compatibility with existing WordPress workflows and enabling seamless AI integration.

---

**Last Updated:** May 30, 2025  
**Next Review:** When Daniel begins live editor integration  
**Contact:** Shanna & Daniel for architecture decisions
