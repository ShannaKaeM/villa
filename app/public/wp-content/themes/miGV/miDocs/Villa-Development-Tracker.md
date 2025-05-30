# Villa Community Development Tracker
**Shanna & Cascade Working Document**

---

## 🎯 **Current Mission**
Build out Villa Community features while keeping everything **AI-ready** for Daniel's live editor integration.

---

## ✅ **Completed Today**
- [x] Cleaned up CMB2 blocks (removed clutter)
- [x] Added AI-ready data attributes to all fields
- [x] Fixed dashboard block structure
- [x] Removed unnecessary meta box render functions
- [x] Created project documentation

---

## 🔄 **Current Issues to Fix**

### **1. Dashboard Blocks Not Showing as Gutenberg Blocks**
- **Problem:** Meta boxes appearing on every page instead of Gutenberg blocks
- **Solution:** Need proper Gutenberg block registration
- **Status:** 🔴 Needs fixing

### **2. Property Showcase Block**
- **Status:** ✅ Working (has proper Twig template)
- **Location:** `templates/blocks/property-showcase.twig`

### **3. Dashboard Functionality**
- **Frontend Dashboard:** ✅ Working
- **User Management:** ✅ Working
- **Property Management:** ✅ Working

---

## 📋 **Next Tasks (Priority Order)**

### **High Priority**
1. **Fix Gutenberg Block Registration**
   - Convert CMB2 meta boxes to proper Gutenberg blocks
   - Create block.json files for each block
   - Add proper block rendering functions

2. **Create Missing Twig Templates**
   - Dashboard layout template
   - Properties management template  
   - Tickets management template
   - Business management template

3. **Test All Dashboard Functions**
   - Verify property creation works
   - Test ticket submission
   - Check business listing functionality

### **Medium Priority**
4. **Enhance Property Showcase**
   - Add more layout options
   - Improve responsive design
   - Add filtering functionality

5. **User Experience Improvements**
   - Better dashboard navigation
   - Improved form styling
   - Mobile responsiveness

6. **Content Management**
   - Add sample properties
   - Create test tickets
   - Set up business listings

### **Low Priority (AI-Ready Prep)**
7. **Component Documentation**
   - Document all CSS classes for live targeting
   - Create component style guide
   - Prepare for React conversion

---

## 🎨 **Design Decisions Made**

### **Block Structure**
- Using CMB2 with AI-ready data attributes
- Each field has `data-live-target` for future live editing
- JSON-friendly structure with `data-json-key`

### **Template Strategy**
- Twig templates for all blocks
- CSS classes follow live-editing patterns
- Responsive-first approach

### **Data Architecture**
- Custom Post Types for core entities
- Meta fields for flexible content
- User roles for permission management

---

## 🚨 **Blockers & Questions**

### **Current Blockers**
1. **CMB2 → Gutenberg Conversion**
   - Need to decide: Keep CMB2 meta boxes OR create proper Gutenberg blocks?
   - Impact: Affects how Daniel integrates live editor

### **Questions for Next Session**
1. Should we create proper Gutenberg blocks now or wait for Daniel?
2. What's the priority: functionality or AI-readiness?
3. Do we need sample content for testing?

---

## 🎯 **Success Criteria**

### **For This Week**
- [ ] Dashboard blocks work properly (either meta boxes OR Gutenberg)
- [ ] All dashboard functions are testable
- [ ] Property showcase displays real data
- [ ] User can create/edit properties, tickets, businesses

### **For Daniel's Integration**
- [ ] All fields have proper data attributes
- [ ] CSS classes follow live-editing patterns
- [ ] JSON structure is consistent
- [ ] Templates are modular and reusable

---

## 📝 **Session Notes**

### **May 30, 2025 - Morning Session**
- Cleaned up CMB2 blocks significantly
- Removed unnecessary meta box render functions
- Added AI-ready data attributes to all fields
- Discovered CMB2 blocks aren't showing as Gutenberg blocks
- Need to decide on block registration approach

### **Next Session Goals**
1. Fix block registration issue
2. Create missing Twig templates
3. Test all dashboard functionality
4. Add sample content for testing

---

## 🔧 **Quick Reference**

### **File Locations**
- **Blocks:** `mu-plugins/villa-cmb2-blocks.php`
- **Dashboard:** `mu-plugins/villa-frontend-dashboard.php`
- **CPTs:** `mu-plugins/villa-dashboard-post-types.php`
- **Templates:** `themes/miGV/templates/blocks/`

### **AI-Ready Pattern**
```php
'attributes' => array(
    'data-ai-field' => 'purpose',
    'data-live-target' => '.css-selector',
    'data-json-key' => 'object.property'
),
```

### **CSS Class Pattern**
```html
<div class="component-name component-name--variant">
    <h2 class="component-title">Title</h2>
    <div class="component-content">Content</div>
</div>
```

---

**Last Updated:** May 30, 2025 10:00 AM  
**Next Update:** After fixing block registration
