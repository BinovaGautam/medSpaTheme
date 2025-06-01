# AI Agent Mandatory Checklist - Complete Protocol Enforcement

---
**Date:** 2024-12-19  
**Protocol Type:** AI Agent Compliance Checklist  
**Status:** ✅ ENFORCED  
**Applies To:** ALL AI Agents on this project  

---

## 🚨 **MANDATORY: Complete This Checklist Before ANY Task Completion**

### **📋 Pre-Task Checklist**
```
□ 1. Read projManager/AI-AGENT-INSTRUCTIONS.md
□ 2. Check current git status (git status)
□ 3. Understand task scope and deliverables
□ 4. Check master-index.json routing patterns for file placement
□ 5. Identify proper documentation routing using routing decision matrix
□ 6. Create task branch if needed (git checkout -b task/[ID]-[description])
```

### **📝 During Task Execution**
```
□ 1. Make incremental commits for major steps
□ 2. Use standardized commit message format
□ 3. Never place documentation files at root level
□ 4. Route all .md files to proper projManager subdirectories per routing engine
□ 5. Validate file placement against master-index.json patterns before saving
□ 6. Add proper frontmatter to all documentation
```

### **✅ Upon Task Completion - MANDATORY**
```
□ 1. Verify all deliverables are complete
□ 2. Stage all changes (git add .)
□ 3. Create completion commit with FULL documentation
□ 4. Include deliverable list in commit message
□ 5. Add task status (✅ COMPLETED) to commit
□ 6. Tag major milestones (git tag -a "vX.X.X-[milestone]")
□ 7. Update any task documentation files
□ 8. Push changes to repository
```

## 🎯 **File Placement Protocol**

### **MANDATORY: Master-Index.json Routing Compliance**
```
🚨 BEFORE placing ANY file, validate against routing patterns:

Content Analysis Questions:
1. Does it contain implementation plans/specs? → execution/planning/
2. Does it contain architectural decisions (ADR-)? → decisions/architectural/  
3. Does it contain requirements (REQ-)? → requirements/refined/
4. Does it contain task tracking (TASK-)? → tasks/pending|completed/
5. Does it contain best practices/patterns? → knowledge/patterns/
6. Does it contain metrics/analytics? → analytics/metrics/
```

### **Enhanced Documentation Routing (MANDATORY):**
| File Content Type | Route To | Routing Pattern Match | Examples |
|-------------------|----------|----------------------|----------|
| **Implementation Plans** | `execution/planning/` | "implementation", "prototype" | PLAN-UX-*, technical specs |
| **UX/UI Planning** | `execution/planning/` | "implementation", "planning" | Design systems, UX flows |
| **Technical Specifications** | `execution/planning/` | "implementation", "prototype" | API specs, architecture plans |
| **Completion Reports** | `execution/documentation/` | "implementation" | Template completion, phase reports |
| **Implementation Status** | `execution/implementations/` | "implementation" | Setup status, fix reports |
| **Decision Records** | `decisions/architectural/` | "decision", "ADR-", "choice" | ADRs, technical choices |
| **Requirements** | `requirements/refined/` | "requirement", "REQ-", "acceptance-criteria" | Feature specs, user stories |
| **Task Updates** | `tasks/completed/` | "task", "TASK-", "todo" | Task completion reports |
| **Best Practices** | `knowledge/patterns/` | "pattern", "lesson", "best-practice" | Design patterns, lessons learned |
| **Performance Metrics** | `analytics/metrics/` | "metric", "report", "dashboard" | Performance data, analytics |
| **AI Protocols** | `ai-governance/` | N/A (Special category) | Agent instructions, protocols |

### **File Placement Validation Script (Run Before Commit):**
```bash
# Validate routing compliance
echo "🔍 Validating file placement against master-index.json patterns..."

# Check for PLAN files in wrong locations
if find projManager/projectDocs/decisions/ -name "PLAN-*" 2>/dev/null | grep -q .; then
    echo "❌ PLAN files found in decisions/ - should be in execution/planning/"
    exit 1
fi

# Check for implementation content in wrong locations  
if find projManager/projectDocs/requirements/ -name "*implementation*" 2>/dev/null | grep -q .; then
    echo "❌ Implementation files found in requirements/ - should be in execution/"
    exit 1
fi

echo "✅ File placement validation passed"
```

### **ROOT LEVEL RESTRICTIONS:**
```
❌ NEVER place these at root level:
- Project documentation (.md files)
- Implementation reports  
- Status updates
- Completion reports
- Task documentation

✅ ONLY these belong at root level:
- Core theme files (functions.php, style.css)
- Build configs (package.json, composer.json)
- Standard WordPress files (README.md, theme.json)
```

## 🔄 **Git Version Control Protocol**

### **Commit Message Format (MANDATORY):**
```
[TYPE]([SCOPE]): [DESCRIPTION] - TASK COMPLETED

Deliverables:
- [Specific file/feature created]
- [Components implemented]

Features Added:
- [Key functionality]
- [System improvements]

Task Status: ✅ COMPLETED
Agent: [Your Agent Name]
Task-ID: [TASK-XXX-YYY]
```

### **Required Commit Types:**
- `feat` - New features/functionality
- `fix` - Bug fixes, error resolution  
- `docs` - Documentation changes
- `refactor` - Code restructuring
- `style` - CSS/styling changes
- `setup` - Initial setup/configuration

### **Git Commands (Execute in Order):**
```bash
# 1. Stage changes
git add .

# 2. Create completion commit
git commit -m "[TYPE]([SCOPE]): [DESCRIPTION] - TASK COMPLETED" \
           -m "Deliverables:" \
           -m "- [List each deliverable]" \
           -m "Task Status: ✅ COMPLETED" \
           -m "Agent: [Your Name]" \
           -m "Task-ID: [TASK-XXX-YYY]"

# 3. Tag milestones (for major completions)
git tag -a "v[X.X.X]-[milestone]" -m "[Description of milestone]"

# 4. Push changes  
git push origin [branch-name]
git push --tags
```

## 🚨 **ENFORCEMENT - Violation Consequences**

### **Tasks will be REJECTED if:**
- ❌ Documentation placed at root level
- ❌ No git commit for task completion  
- ❌ Vague or missing commit messages
- ❌ No deliverables listed in commit
- ❌ Missing task status in commit
- ❌ Files not routed to proper projManager structure
- ❌ Manual date entry attempted (git handles dates automatically)

### **Required Actions for Violations:**
1. **STOP** immediately upon discovering violation
2. **CORRECT** file placement and git history
3. **RE-COMMIT** with proper protocol compliance
4. **DOCUMENT** the correction in commit message

## 📊 **Quality Gates**

### **File Organization Check:**
```bash
# Verify no documentation at root
find . -maxdepth 1 -name "*.md" -not -name "README.md" | wc -l
# Should return 0 (only README.md allowed at root)
```

### **Git Compliance Check:**
```bash
# Verify task completion commits
git log --grep="TASK COMPLETED" --oneline
# Should show properly formatted completion commits

# Check commit timestamps (automatically tracked by git)
git log --pretty=format:"%h %ad %s" --date=short
```

### **Documentation Structure Check:**
```bash
# Verify proper projManager organization
ls -la projManager/projectDocs/
# Should show organized subdirectories
```

## 🎯 **Success Criteria**

### **A task is considered complete ONLY when:**
✅ All deliverables are functional and tested  
✅ All files are in proper projManager locations  
✅ Git commit includes full documentation  
✅ Commit message follows standardized format  
✅ Task status is clearly marked as completed  
✅ Major milestones are tagged in git  
✅ No documentation files remain at root level  
✅ All changes are pushed to repository  
✅ Git automatically tracks all timestamps (no manual dates)

## 📈 **Project Benefits**

### **This protocol ensures:**
- ✅ **Complete traceability** of all project changes
- ✅ **Professional documentation** organization
- ✅ **Client-ready** project structure
- ✅ **Easy rollback** capability
- ✅ **Clear accountability** for all changes
- ✅ **Automated reporting** from git history
- ✅ **Scalable project** management
- ✅ **Automatic timestamp** tracking via git

## 📅 **Git Date Management**

### **Git Automatically Handles:**
- ✅ **Commit timestamps** - When changes were made
- ✅ **Author dates** - When commits were authored  
- ✅ **Tag timestamps** - When milestones were created
- ✅ **Branch dates** - When branches were created/merged
- ✅ **File modification** tracking

### **AI Agents Should NEVER:**
- ❌ Manually add dates to commit messages
- ❌ Try to format or calculate dates  
- ❌ Include date strings in documentation
- ❌ Override git's automatic timestamping

### **Date Retrieval Commands:**
```bash
# View commits with dates
git log --pretty=format:"%h %ad %s" --date=short

# Generate timeline reports
git log --pretty=format:"- %s (%ad)" --date=short --grep="TASK COMPLETED"

# Check specific file history with dates
git log --follow --pretty=format:"%ad %s" --date=short -- [filename]
```

---

## 🎯 **PROTOCOL STATUS: ENFORCED**

**This checklist is MANDATORY for all AI agents. No exceptions. Task completion without protocol compliance will be rejected and must be corrected immediately.**

**Git handles ALL date tracking automatically - AI agents must never manually manage dates.**

**Reference Files:**
- `projManager/projectDocs/ai-governance/GIT-VERSION-CONTROL-PROTOCOL.md`
- `projManager/projectDocs/ai-governance/AI-AGENT-PROTOCOLS-UPDATE.md` 
