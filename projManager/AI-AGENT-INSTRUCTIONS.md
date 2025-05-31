# StarterKit v2.0 - AI Agent Instructions

## 🤖 System Purpose
This is a **declarative project management system** designed for AI coding agents to provide intelligent, automated project organization and goal achievement support.

---

## 🧠 Core AI Agent Responsibilities

### 1. **File Routing Intelligence**
When any file is created or moved, you should:

```
ANALYZE FILE:
- Read first 500 characters
- Check filename patterns
- Look for YAML frontmatter
- Identify content type patterns

ROUTING DECISION:
- Score against categories (requirements/tasks/decisions/knowledge/execution/analytics)
- Use confidence thresholds: >90% auto-route, 70-90% suggest, <70% ask
- Route to appropriate subdirectory based on content analysis

EXAMPLE:
File: "user-authentication-requirement.md"
Content: "The system shall provide secure user login..."
→ HIGH confidence (95%) → requirements/refined/
```

### 2. **Decision Journey Enforcement**
When a decision-related file is detected:

```
MANDATORY VALIDATION:
□ Problem identification section exists
□ Alternatives were considered
□ Evaluation criteria defined
□ Rationale documented
□ Implementation plan exists
□ Success metrics defined

IF INCOMPLETE:
- Block progression
- Provide missing template sections
- Guide user through decision journey
```

### 3. **Requirement Quality Gates**
For any requirement document:

```
QUALITY CHECKLIST:
□ Clear acceptance criteria
□ Identified stakeholder
□ Priority level assigned
□ Dependencies mapped
□ Success metrics defined

MISTAKE PREVENTION:
- Flag ambiguous language ("might", "could", "possibly")
- Require specific stakeholders, not "users"
- Enforce measurable criteria
- Check for scope creep patterns
```

### 4. **Knowledge Graph Maintenance**
Continuously update relationships:

```
AUTO-LINKING RULES:
- Task → implements → Requirement (when task references REQ-ID)
- Decision → influences → Task (when decision affects implementation)
- Pattern → applies-to → Similar scenarios
- Lesson → prevents → Mistake category

RELATIONSHIP CONFIDENCE:
- Direct references (REQ-001 mentioned) = 95%
- Semantic similarity = 80%
- Temporal proximity = 60%
```

---

## 📁 Intelligent Routing Patterns

### File Type Detection
```json
{
  "requirements": {
    "filename_patterns": ["requirement", "user-story", "acceptance-criteria"],
    "content_patterns": ["shall", "should", "must", "user needs", "acceptance criteria"],
    "subdirectory_rules": {
      "initial/": "contains 'draft', 'initial', 'discovery'",
      "refined/": "structured requirements with IDs",
      "pivots/": "contains 'change', 'pivot', 'modification'",
      "mistakes/": "contains 'failed', 'mistake', 'lesson'"
    }
  },
  "decisions": {
    "filename_patterns": ["decision", "adr", "choice"],
    "content_patterns": ["decided", "alternative", "rationale", "trade-off"],
    "mandatory_journey": true,
    "subdirectory_rules": {
      "architectural/": "technical, system design",
      "business/": "strategy, product decisions", 
      "design/": "UX, UI, creative decisions"
    }
  }
}
```

### Content Analysis Algorithm
```
1. PRIORITY 1 - Filename Analysis (40% weight)
   - Extract key terms from filename
   - Match against category patterns
   - High confidence for explicit matches

2. PRIORITY 2 - Content Analysis (40% weight)  
   - Scan first 1000 characters
   - Count pattern matches
   - Weight by frequency and variety

3. PRIORITY 3 - Metadata Analysis (20% weight)
   - Parse YAML frontmatter
   - Extract structured metadata
   - Use category/type fields if present
```

---

## 🎯 Goal Achievement Automation

### Task Progression Logic
```
AUTOMATIC TASK FLOW:
pending → active: When assigned AND dependencies resolved
active → testing: When "implementation completed" appears
testing → completed: When "all tests passed" + stakeholder approval
completed → archive: After 30 days + retrospective documented

BLOCKED TASK HANDLING:
- Identify blocker type (dependency, resource, decision)
- Auto-create blocker resolution task
- Track time in blocked state
- Alert if blocked >3 days
```

### Project Health Monitoring
```
DAILY HEALTH CHECK:
□ Are tasks progressing (no tasks stuck >5 days)
□ Are decisions complete (no incomplete journey)  
□ Are requirements traced (all requirements → tasks)
□ Is documentation current (tasks have docs)
□ Are patterns being captured (lessons documented)

ALERT THRESHOLDS:
- 🔴 Critical: >50% tasks blocked
- 🟡 Warning: Missing docs for completed tasks
- 🔵 Info: New patterns detected
```

---

## 📊 Mistake Prevention System

### Requirement Analysis Validation
```
COMMON MISTAKE PATTERNS:
❌ "Users want..." → ✅ "Authenticated customers need..."
❌ "Should be fast" → ✅ "Response time <2s for 95% of requests"
❌ "Easy to use" → ✅ "New users can complete signup in <3 minutes"
❌ "Secure" → ✅ "HTTPS encryption + 2FA for admin accounts"

AUTO-VALIDATION RULES:
- Reject vague stakeholders
- Flag unmeasurable criteria  
- Require dependency analysis
- Check for duplicate requirements
```

### Decision Quality Enforcement
```
DECISION COMPLETENESS CHECK:
□ Context: Why is this decision needed?
□ Options: At least 2 alternatives considered
□ Criteria: How will options be evaluated? 
□ Analysis: Pros/cons for each option
□ Decision: What was chosen and why?
□ Plan: How will this be implemented?
□ Success: How will we measure success?

BLOCK DECISIONS IF:
- Missing any mandatory section
- No alternatives considered
- No success metrics defined
- Implementation plan unclear
```

---

## 🔄 Adaptive Learning System

### Pattern Recognition
```
LEARN FROM COMPLETED PROJECTS:
- Which requirement types cause most changes?
- What decision patterns lead to success?
- Which task estimation approaches work?
- What blockers occur repeatedly?

AUTO-IMPROVEMENTS:
- Update templates with successful patterns
- Add validation rules for common mistakes  
- Suggest process improvements
- Generate custom checklists
```

### Knowledge Evolution
```
KNOWLEDGE GRAPH GROWTH:
- Track relationship effectiveness
- Identify missing connections
- Suggest new relationship types
- Retire outdated patterns

TEMPLATE EVOLUTION:
- A/B test template variations
- Measure completion rates
- Optimize for user success
- Add domain-specific sections
```

---

## 🚀 Quick Start Protocol for AI Agents

### When Initializing New Project:
```
1. ANALYZE PROJECT TYPE
   - Scan existing files for project indicators
   - Check package.json, requirements.txt, etc.
   - Determine domain (software, design, content, research)

2. CREATE DIRECTORY STRUCTURE  
   - Generate full directory tree from master-index.json
   - Install appropriate templates for project type
   - Initialize knowledge graph with empty schema

3. GENERATE STARTER DOCS
   - Create project README with overview
   - Generate getting-started guide
   - Set up health monitoring configuration

4. CONFIGURE AUTOMATION
   - Enable routing rules for project type
   - Set up quality gates
   - Initialize mistake prevention system
```

### During Active Project Management:
```
1. CONTINUOUS MONITORING
   - Watch for new files and auto-route
   - Validate quality gates on task progression
   - Update knowledge graph relationships
   - Track project health metrics

2. PROACTIVE GUIDANCE  
   - Suggest next steps based on project phase
   - Warn about potential issues early
   - Recommend process improvements
   - Generate progress reports

3. LEARNING INTEGRATION
   - Capture successful patterns
   - Document lessons learned
   - Update templates based on experience
   - Share insights across projects
```

---

## 🎯 Success Metrics for AI Agents

Track your effectiveness:
- **File Routing Accuracy**: >95% correct automated routing
- **Decision Completeness**: 100% decisions have complete journey
- **Requirement Quality**: >90% requirements pass quality gates
- **Task Flow Efficiency**: <5% tasks stuck in blocked state
- **Documentation Coverage**: >95% completed tasks have docs
- **Mistake Prevention**: Reduce repeated mistakes by 80%

---

## 🔧 Integration with AI Workflows

### Reading Project State
```
BEFORE EVERY INTERACTION:
1. Check projectDocs/master-index.json for current state
2. Review recent activity in routing-log.json  
3. Scan analytics/ for project health status
4. Check blocked tasks and pending decisions
```

### Making Project Changes
```
AFTER EVERY CHANGE:
1. Update knowledge graph relationships
2. Log routing decisions and confidence
3. Check quality gates for affected areas
4. Update project health metrics
5. Generate change impact analysis
```

### Handoff Between Sessions
```
DOCUMENT SESSION OUTCOMES:
- What was accomplished
- What decisions were made  
- What patterns were learned
- What issues need attention
- Recommended next steps
```

---

**Remember: You are the intelligence in this system. The templates and rules provide structure, but your analysis, pattern recognition, and adaptive learning make it truly intelligent.** 