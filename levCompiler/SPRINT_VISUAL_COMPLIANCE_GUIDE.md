# 🏃‍♂️ Sprint Visual Compliance Integration Guide

## 🎯 **When to Trigger Visual Compliance During Sprints**

### **📋 Task-Based Triggers**
```bash
# ✅ After implementing a new UI component
# ✅ Before marking a task as "Done"
# ✅ During code review process
# ✅ When design changes are implemented
# ✅ Before sprint demo/showcase
```

### **🔄 Automated Sprint Integration Points**

#### **1. Pre-Task Completion Check**
```bash
# Run before marking task complete
node tools/visual-validation/cli/visual-validate-simple.js validate \
  --url http://medspaa.local/treatments/ \
  --auto --chat

# This will:
# - Capture current implementation screenshots
# - Save to temp directory (max 100 files, auto-cleanup)
# - Generate compliance report
# - Update task context with findings
```

#### **2. Design Implementation Validation**
```bash
# When you have design specifications to compare against
node tools/visual-validation/cli/visual-validate-simple.js validate \
  --url http://medspaa.local/your-implemented-page/ \
  --target levCompiler/project_context/designs/DESIGN_SYSTEM_OVERVIEW.md \
  --auto --chat
```

#### **3. Sprint Milestone Checks**
```bash
# Weekly/bi-weekly sprint compliance audit
node tools/visual-validation/cli/temp-screenshots.js list
node tools/visual-validation/cli/temp-screenshots.js info

# Clean up old screenshots before sprint end
node tools/visual-validation/cli/temp-screenshots.js cleanup
```

---

## **📊 Integration with Task Management Workflow**

### **Automatic Task Context Integration**
The visual compliance workflow automatically:

1. **📂 Reads Current Task Context**
   - Location: `levCompiler/project_context/tasks/`
   - Retrieves sprint goals and implementation requirements
   - Maps current task to compliance requirements

2. **🎨 References Design Specifications**
   - Location: `levCompiler/project_context/designs/`
   - Accesses semantic token definitions
   - Validates against design system specifications

3. **📝 Updates Task Status**
   - Compliance findings integrated into task notes
   - Quality gate status updated automatically
   - Sprint-level impact assessment generated

---

## **🎯 Quick Sprint Commands**

### **Start-of-Sprint Setup**
```bash
# Initialize temp screenshot system
node tools/visual-validation/cli/temp-screenshots.js info

# Clear any old screenshots
node tools/visual-validation/cli/temp-screenshots.js clear --force
```

### **During Development**
```bash
# Quick validation check
node tools/visual-validation/cli/visual-validate-simple.js validate \
  --url http://medspaa.local/[your-page] --auto

# Check temp storage
node tools/visual-validation/cli/temp-screenshots.js list
```

### **Pre-Sprint Demo**
```bash
# Comprehensive validation for demo prep
node tools/visual-validation/cli/visual-validate-simple.js validate \
  --url http://medspaa.local/treatments/ \
  --auto --chat

# Generate presentable screenshots
node tools/visual-validation/cli/temp-screenshots.js list --verbose
```

### **End-of-Sprint Cleanup**
```bash
# Archive important screenshots before cleanup
# (Manual copy if needed)

# Clean temp directory for next sprint
node tools/visual-validation/cli/temp-screenshots.js clear --force
```

---

## **📱 Screenshot Access During Sprint Work**

### **Direct File URLs (Click to Open)**
After any validation run, you get direct file URLs:
```
file:///Users/ysingh/DevKinsta/public/medspaa/wp-content/themes/medSpaTheme/levCompiler/temp/screenshots/temp_screenshot_[timestamp]_[viewport]_[hash].png
```

### **Local File Paths (For Development Tools)**
```
temp/screenshots/temp_screenshot_[timestamp]_[viewport]_[hash].png
```

---

## **🎪 Integration with Development Workflow**

### **VS Code Integration**
```bash
# Add to your VS Code tasks.json
{
  "label": "Visual Compliance Check",
  "type": "shell",
  "command": "node",
  "args": [
    "tools/visual-validation/cli/visual-validate-simple.js",
    "validate",
    "--url", "http://medspaa.local/treatments/",
    "--auto", "--chat"
  ],
  "group": "test",
  "presentation": {
    "echo": true,
    "reveal": "always"
  }
}
```

### **Git Pre-commit Hook Integration**
```bash
# Add to .git/hooks/pre-commit
#!/bin/sh
echo "🔍 Running visual compliance check..."
node tools/visual-validation/cli/visual-validate-simple.js validate \
  --url http://medspaa.local/treatments/ --auto

# Only proceed if compliance passes
if [ $? -eq 0 ]; then
  echo "✅ Visual compliance passed"
  exit 0
else
  echo "❌ Visual compliance failed - check screenshots"
  exit 1
fi
```

---

## **🎯 Trigger Patterns for Voice/Chat Commands**

If using voice commands or chat interfaces with levCompiler:

### **Quick Validation Triggers**
- *"Check visual compliance"*
- *"Validate current implementation"*
- *"Take screenshots for review"*
- *"Run design system check"*

### **Comprehensive Analysis Triggers**
- *"Full visual audit"*
- *"Design token compliance check"*
- *"Screenshot comparison analysis"*
- *"Semantic token validation"*

---

## **📊 Compliance Reporting Integration**

### **Task-Level Reporting**
```bash
# Generate task-specific compliance report
node tools/visual-validation/cli/visual-validate-simple.js validate \
  --url http://medspaa.local/treatments/ \
  --auto --chat \
  --task-integration
```

### **Sprint-Level Dashboard**
```bash
# View compliance trends and storage usage
node tools/visual-validation/cli/temp-screenshots.js info

# List recent validation runs
node tools/visual-validation/cli/temp-screenshots.js list --verbose
```

---

## **⚡ Quick Reference Commands**

```bash
# 🚀 QUICK START
node tools/visual-validation/cli/visual-validate-simple.js validate --url http://medspaa.local/treatments/ --auto

# 📊 CHECK STATUS  
node tools/visual-validation/cli/temp-screenshots.js info

# 📱 VIEW SCREENSHOTS
node tools/visual-validation/cli/temp-screenshots.js list

# 🧹 CLEANUP
node tools/visual-validation/cli/temp-screenshots.js cleanup
``` 
