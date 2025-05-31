# Documentation Consistency Validation Guide

## 🎯 **Purpose**
This document ensures all .md files in StarterKit v2.0 remain consistent and don't contradict each other.

---

## 📁 **Documentation Files Inventory**

### **Root Level Documentation**
| File | Purpose | Key Messages | Dependencies |
|------|---------|--------------|--------------|
| `README.md` | System overview & capabilities | AI-agent-friendly, intelligent routing, quality gates | Must align with AI-AGENT-INSTRUCTIONS.md |
| `SETUP-GUIDE.md` | Initialization procedures | 7-step setup, project type detection | Must match master-index.json structure |
| `AI-AGENT-INSTRUCTIONS.md` | Operating procedures | File routing, quality gates, automation | Must match README.md capabilities |
| `USAGE-STRUCTURE.md` | Folder structure guide | Entry points, integration patterns | Must reflect actual directory structure |

### **Module Documentation**
| File | Purpose | Key Messages | Dependencies |
|------|---------|--------------|--------------|
| `projectDocs/SYSTEM-OVERVIEW.md` | Modular architecture | 8 modules, cross-module wiring | Must align with modular-system-config.json |

### **Template Documentation**
| File | Purpose | Key Messages | Dependencies |
|------|---------|--------------|--------------|
| `templates/requirement-template.md` | Requirement validation | AI validation, quality gates | Must match module requirements/index.json |
| `templates/decision-template.md` | Decision journey enforcement | 6 mandatory phases, alternatives | Must match decisions/index.json rules |

---

## ✅ **Consistency Validation Checklist**

### **1. Directory Structure Consistency**
- [ ] All docs reference the same directory structure
- [ ] File paths are accurate across all documentation
- [ ] Line counts are current and accurate
- [ ] Module counts (8 modules) are consistent

### **2. Feature Consistency**
- [ ] AI capabilities described consistently across docs
- [ ] Quality gates mentioned uniformly
- [ ] Routing confidence thresholds match (90%/70%/50%)
- [ ] Health monitoring metrics align

### **3. Workflow Consistency** 
- [ ] AI agent initialization steps are the same
- [ ] File processing workflows match
- [ ] Template usage is consistent
- [ ] Quality gate enforcement aligns

### **4. Technical Details Consistency**
- [ ] JSON file references are accurate
- [ ] Configuration parameters match
- [ ] Automation rules align across docs
- [ ] Success metrics are identical

---

## 🚨 **Common Inconsistency Patterns to Watch**

### **File References**
- ❌ **Wrong**: "master-index.json (474 lines)"
- ✅ **Correct**: "master-index.json (574 lines)" 

### **Directory Paths**
- ❌ **Wrong**: Mixing `projectDocs/` and other path references
- ✅ **Correct**: Consistent `starterKitv2/projectDocs/` structure

### **Module Counts**
- ❌ **Wrong**: Mentioning different numbers of modules
- ✅ **Correct**: Always reference "8 modules" (requirements, tasks, decisions, knowledge, execution, analytics, automation, core)

### **Confidence Thresholds**
- ❌ **Wrong**: Different percentages in different docs
- ✅ **Correct**: Always 90% auto-route, 70% suggest, 50% manual

---

## 🔧 **Validation Process**

### **Before Any Documentation Update:**
1. **Check References**: Ensure all file paths and line counts are current
2. **Verify Claims**: Confirm all feature claims are accurate
3. **Cross-Reference**: Check that changes don't contradict other docs
4. **Test Consistency**: Run through the full workflow described

### **Regular Maintenance Tasks:**
- **Weekly**: Verify line counts in key files
- **After Changes**: Update all referencing documents
- **Before Releases**: Complete consistency validation

---

## 📊 **Key Numbers to Keep Consistent**

| Item | Current Value | Referenced In |
|------|---------------|---------------|
| Master Index Lines | 574 | USAGE-STRUCTURE.md, AI workflow |
| Module Config Lines | 160 | USAGE-STRUCTURE.md, documentation references |
| Module Count | 8 | All system overview docs |
| Auto-route Threshold | 90% | README.md, AI-AGENT-INSTRUCTIONS.md, SETUP-GUIDE.md |
| Suggest Threshold | 70% | README.md, AI-AGENT-INSTRUCTIONS.md, SETUP-GUIDE.md |
| Manual Threshold | 50% | README.md, AI-AGENT-INSTRUCTIONS.md, SETUP-GUIDE.md |

---

## 🎯 **Message Consistency Matrix**

### **Core System Messages (Must be identical across docs):**
- "AI-agent-friendly project management system"
- "Intelligent file routing based on content analysis"
- "Quality gates prevent common mistakes"
- "Complete decision journey enforcement"
- "Knowledge graph automation"
- "95%+ file routing accuracy"
- "100% decision journey completeness"
- "80% mistake reduction through learning"

### **Architecture Messages (Must align):**
- "Modular architecture with 8 modules"
- "Cross-module wiring with confidence scoring"
- "Event-driven integration"
- "Self-organizing and learning system"

---

## 🔄 **Update Propagation Rules**

### **When master-index.json changes:**
- Update line count in USAGE-STRUCTURE.md
- Verify AI workflow references in README.md
- Check configuration references in SETUP-GUIDE.md

### **When module configs change:**
- Update SYSTEM-OVERVIEW.md details
- Verify template validation rules
- Check automation references

### **When templates change:**
- Update quality gate descriptions
- Verify validation rule consistency
- Check example references

---

**Consistency is critical for AI agent understanding. Any contradiction can cause confusion and system failures.** 