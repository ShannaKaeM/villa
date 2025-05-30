---
description: AI-Powered Live Editor Tech Stack & Workflow Documentation
---

# AI-Powered Live Editor Tech Stack & Workflow

## 🎯 **Project Vision**
Building an AI-powered WordPress plugin that enables live visual editing with real-time preview, responsive breakpoint switching, and AI agent integration.

### **Core Concept**
- Click on any component (hero, cards, etc.) → Fields appear in left sidebar
- Fill out fields → Live preview updates instantly
- Switch between mobile/tablet/desktop breakpoints
- AI agents can generate and modify content in real-time

---

## 🏗️ **Technical Architecture**

### **Data Layer (Foundation)**
```
CMB2 Fields (Enhanced) → JSON API → React UI → Live Preview
```

#### **CMB2 Enhanced System**
- **Custom CMB2 Field Types** - Daniel's page builder field extension
- **JSON-Friendly Structure** - All data serializable for AI/API
- **Data Attributes** - Live-editing target selectors (`data-component`, `data-field`)
- **Field Metadata** - AI generation hints and validation rules

#### **Storage & Retrieval**
- **WordPress Meta Tables** - Primary data storage
- **REST API Endpoints** - Real-time data access
- **WebSocket/PostMessage** - Live updates between frames
- **Version Control** - Change tracking for AI learning

### **Presentation Layer**
```
Twig Templates → Web Components → Live DOM → AI Targets
```

#### **Template System**
- **Timber/Twig** - Server-side rendering with data binding
- **Component Templates** - Reusable UI blocks with consistent structure
- **Data Binding** - CMB2 field → Template variable mapping
- **CSS Custom Properties** - Theme integration via theme.json

#### **Frontend Components**
- **Web Components** - Encapsulated UI elements
- **CSS Grid/Flexbox** - Responsive layouts
- **Theme Integration** - Uses CSS custom properties from theme.json
- **Progressive Enhancement** - Works without JavaScript

### **Interface Layer**
```
Click Detection → Sidebar UI → Field Updates → Live Preview
```

#### **Live Editor Interface**
- **React Sidebar** - Dynamic field rendering based on CMB2 definitions
- **Click Overlay System** - Component selection and highlighting
- **Responsive Switcher** - Real-time breakpoint testing
- **Real-time Updates** - DOM manipulation without page refresh

#### **AI Integration**
- **Agent Communication** - REST API endpoints for AI interaction
- **Content Generation** - Text, images, layouts based on context
- **Context Awareness** - Site data, user preferences, content history
- **Learning System** - Improves suggestions over time

---

## 🛠️ **Development Workflow**

### **1. Component Creation Process**

#### **Step 1: Define CMB2 Fields**
```php
// Enhanced CMB2 with Daniel's page builder field
$cmb->add_field(array(
    'name' => 'Hero Section',
    'id' => 'hero_content',
    'type' => 'page_builder', // Daniel's custom field type
    'ai_hints' => array(
        'generate' => 'hero_content',
        'context' => 'business_landing',
        'suggestions' => array('compelling_headline', 'call_to_action')
    ),
    'live_target' => '[data-component="hero"]',
    'responsive' => true
));
```

#### **Step 2: Create Twig Template**
```twig
{# hero-section.twig #}
<section class="hero" 
         data-component="hero" 
         data-cmb2-field="hero_content"
         data-ai-context="landing_hero">
    <div class="hero-content">
        <h1 data-field="title" data-ai-type="headline">
            {{ hero.title|default('Your Compelling Headline') }}
        </h1>
        <p data-field="subtitle" data-ai-type="description">
            {{ hero.subtitle|default('Supporting description text') }}
        </p>
        <a href="{{ hero.cta_url }}" 
           class="btn btn-primary" 
           data-field="cta" 
           data-ai-type="call_to_action">
            {{ hero.cta_text|default('Get Started') }}
        </a>
    </div>
</section>
```

#### **Step 3: Register Component**
```php
// Auto-registration via CMB2 enhanced system
villa_register_component('hero_section', array(
    'template' => 'hero-section.twig',
    'fields' => 'hero_content',
    'category' => 'layout',
    'ai_enabled' => true,
    'responsive_breakpoints' => array('mobile', 'tablet', 'desktop')
));
```

### **2. Live Editing Workflow**

#### **User Interaction Flow**
1. **Click Component** → Overlay highlights element, sidebar opens with relevant fields
2. **Edit Fields** → React form renders CMB2 fields with live validation
3. **Real-time Update** → Changes apply instantly via DOM manipulation
4. **Save Changes** → Data persists to WordPress meta tables via REST API

#### **AI Generation Flow**
1. **Context Analysis** → AI reads site data, current content, user intent
2. **Content Generation** → AI creates field values based on context and hints
3. **Live Preview** → Generated content appears instantly in preview
4. **User Refinement** → Human can edit, approve, or regenerate AI suggestions

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
    container-type: inline-size;
}

@container (max-width: 768px) {
    [data-component] {
        /* Mobile-specific styles */
    }
}
```

#### **Live Responsive Testing**
- **Breakpoint Switcher** - Toggle between device sizes in real-time
- **Container Queries** - Modern responsive design with container queries
- **Device Simulation** - Accurate mobile/tablet preview with touch simulation

---

## 🔧 **Technical Stack**

### **Backend (WordPress)**
- **WordPress Core 6.0+** - CMS foundation with block editor support
- **CMB2 Enhanced** - Daniel's page builder extension with live editing
- **Timber/Twig 2.0+** - Modern template engine with component support
- **Custom Post Types** - Structured content (Properties, Businesses, etc.)
- **REST API Extended** - Custom endpoints for live editing
- **WebSocket Support** - Real-time communication (optional)

### **Frontend (Live Editor)**
- **React 18+** - UI framework with concurrent features
- **Web Components** - Browser-native reusable elements
- **CSS Custom Properties** - Dynamic theming and responsive design
- **PostMessage API** - Secure cross-frame communication
- **Service Workers** - Offline capabilities and caching

### **AI Integration**
- **OpenAI API** - GPT models for content generation
- **Custom AI Agents** - Specialized tasks (SEO, accessibility, etc.)
- **Context Processors** - Site data analysis and user behavior
- **Learning Algorithms** - Continuous improvement of suggestions

### **Development Tools**
- **Composer** - PHP dependency management
- **npm/Yarn** - JavaScript package management
- **Webpack/Vite** - Modern asset bundling and HMR
- **Git** - Version control with branching strategy
- **Local by Flywheel** - WordPress development environment

---

## 📁 **File Structure**

```
wp-content/
├── themes/your-theme/
│   ├── templates/           # Twig component templates
│   │   ├── components/      # Reusable UI components
│   │   ├── layouts/         # Page layout templates
│   │   └── partials/        # Template partials
│   ├── assets/
│   │   ├── css/            # Compiled stylesheets
│   │   ├── js/             # JavaScript bundles
│   │   └── images/         # Static images
│   ├── src/                # Source files
│   │   ├── scss/           # Sass stylesheets
│   │   ├── js/             # JavaScript modules
│   │   └── components/     # Component-specific assets
│   ├── theme.json          # WordPress theme configuration
│   └── functions.php       # Theme initialization
├── mu-plugins/
│   ├── ai-live-editor/     # Main plugin directory
│   │   ├── core/           # CMB2 enhancements and core functionality
│   │   ├── components/     # Component registration and management
│   │   ├── api/           # REST API endpoints
│   │   ├── ai/            # AI integration and agents
│   │   ├── assets/        # Plugin assets (React app, etc.)
│   │   └── ai-live-editor.php # Main plugin file
│   └── project-specific/   # Custom functionality per project
│       ├── villa-community/ # Villa-specific features
│       └── other-projects/  # Other project customizations
└── uploads/
    ├── ai-cache/           # Cached AI-generated content
    └── component-assets/   # User-uploaded component assets
```

---

## 🚀 **Implementation Strategy**

### **Phase 1: Foundation (Current - ✅ Complete)**
- ✅ CMB2 field definitions with data attributes
- ✅ Twig template system with component structure
- ✅ Component registration system
- ✅ Theme integration with CSS custom properties
- ✅ Basic dashboard functionality

### **Phase 2: Live Editor Core (Next)**
- React sidebar interface with CMB2 field rendering
- Click-to-edit overlay system with component detection
- Real-time field updates via PostMessage/REST API
- Responsive breakpoint switcher with live preview

### **Phase 3: AI Integration**
- AI agent communication layer with context awareness
- Content generation endpoints with field-specific hints
- Context-aware suggestions based on site data
- Learning and improvement system with user feedback

### **Phase 4: Advanced Features**
- Multi-site management from single interface
- Version control and rollback functionality
- Performance optimization and caching
- Plugin marketplace integration

---

## 🎯 **Key Principles**

### **AI-First Design**
- Every field has AI generation capability with context hints
- Context-aware content suggestions based on site data
- Learning from user preferences and content patterns
- Seamless human-AI collaboration with easy refinement

### **Developer Experience**
- Minimal code approach - less boilerplate, more functionality
- JSON-friendly architecture for easy API integration
- Familiar CMB2 syntax with enhanced capabilities
- Easy component creation with auto-registration

### **User Experience**
- Live visual editing without page refresh
- Intuitive click-to-edit interface
- Real-time responsive design testing
- Contextual AI suggestions that make sense

### **Performance & Scalability**
- Lazy loading components and assets
- Efficient DOM updates with minimal reflow
- Cached AI responses with smart invalidation
- Optimized asset delivery with modern bundling

---

## 🔄 **Integration Points**

### **Current Villa Project → Live Editor**
1. **CMB2 Fields** → Enhanced with live editing capabilities
2. **Twig Templates** → Add data attributes for live targeting
3. **Dashboard System** → Integrate with live editor interface
4. **Theme Integration** → Maintain CSS custom properties approach

### **Daniel's System Integration**
1. **Page Builder Field** → Replaces traditional CMB2 meta boxes
2. **React Sidebar** → Renders CMB2 fields dynamically
3. **Live Preview** → Uses existing Twig templates with real-time updates
4. **AI Layer** → Adds content generation to existing field structure

---

This architecture provides a solid foundation for Daniel's vision while maintaining compatibility with existing WordPress workflows and enabling seamless AI integration. The current Villa Community setup aligns perfectly with this approach and will require minimal changes when Daniel's live editor system is ready.

**Last Updated:** May 30, 2025  
**Status:** Foundation Complete, Ready for Live Editor Integration  
**Next Steps:** Await Daniel's CMB2 page builder field implementation
