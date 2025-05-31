# StarterKit v2.0 - Versioning & Iteration System

## 🎯 **AI Agent Progression Model**

StarterKit v2.0 uses **iteration-based progression** instead of time-based scheduling, optimized for AI agent workflows.

---

## 📊 **Core Numbering Systems**

### **1. Entity Identification**
```
Requirements: REQ-[CATEGORY]-[NUMBER]
Tasks:        TASK-[REQ-ID]-[NUMBER] 
Decisions:    ADR-[NUMBER]
Patterns:     PATTERN-[TYPE]-[NUMBER]
Sessions:     SESSION-[TYPE]-[NUMBER]
```

### **2. Iteration Cycles**
```
iteration-0:    System initialization
iteration-1-2:  Discovery and initial analysis
iteration-3:    Processing and refinement
iteration-4:    Validation and approval
iteration-5+:   Implementation cycles
iteration-10:   First major review cycle
iteration-30:   Effectiveness assessment cycle
iteration-90:   Long-term impact evaluation cycle
```

### **3. Version Progression**
```
v2.0:     Current StarterKit system version
v2.1:     Minor updates and enhancements
v3.0:     Major architectural changes
```

---

## 🔄 **Iteration-Based Workflows**

### **Requirement Lifecycle**
| Phase | Iteration Target | Progression Trigger |
|-------|------------------|-------------------|
| Discovery | iteration-1-2 | Stakeholder input collected |
| Analysis | iteration-3 | Requirements categorized and prioritized |
| Validation | iteration-4 | Quality gates passed |
| Tracking | continuous | Change requests and updates |

### **Task Progression**
| Status | Alert Threshold | Escalation Trigger |
|--------|----------------|-------------------|
| Pending | iteration-2 | Dependencies unresolved |
| Active | iteration-3 | No progress indicators |
| Blocked | iteration-5 | Critical escalation needed |
| Testing | iteration-2 | Validation incomplete |

### **Decision Journey**
| Phase | Review Cycle | Outcome Evaluation |
|-------|-------------|-------------------|
| Implementation | iteration-10 | Initial results assessment |
| Effectiveness | iteration-30 | Performance against criteria |
| Long-term Impact | iteration-90 | Strategic value delivered |

---

## 📈 **Metrics & Measurements**

### **Velocity Tracking**
- **Tasks Completed Per Iteration**: Count of completed tasks in each cycle
- **Average Cycle Iterations**: Iterations from active to completed status  
- **Velocity Trend**: Iteration-over-iteration completion rate improvement

### **Quality Indicators**
- **Rework Rate**: Percentage requiring additional iteration cycles
- **Progression Efficiency**: Tasks completing within target iterations
- **Escalation Frequency**: Issues requiring management intervention

### **Blockage Management**
- **Blockage Rate**: Percentage of tasks becoming blocked per iteration
- **Average Blockage Iterations**: Cycles spent in blocked status
- **Resolution Efficiency**: Iterations from blocked to unblocked

---

## 🚨 **Alert & Escalation System**

### **Iteration-Based Thresholds**
```
Warning Level:    iteration-3-same-status
Alert Level:      iteration-5-same-status  
Critical Level:   iteration-7-same-status
```

### **Escalation Rules**
```
After iteration-3:  Notify manager
After iteration-5:  Escalate to project lead
After iteration-7:  Executive escalation required
```

### **Quality Gate Triggers**
```
Stagnation Alert:       No progress for iteration-5
Validation Failure:     Quality gates fail twice  
Stakeholder Delay:      No approval for iteration-7
```

---

## 🔧 **Implementation Guidelines**

### **For AI Agents**
1. **Track iteration numbers** instead of dates/times
2. **Use entity IDs** for all cross-references
3. **Progress through cycles** based on completion criteria
4. **Escalate based on iteration counts** not time elapsed

### **For System Integration**
1. **Update frequencies** tied to iteration completion
2. **Review cycles** triggered by iteration milestones
3. **Metrics calculation** per iteration cycle
4. **Report generation** every N iterations

### **For Cross-Module Coordination**
1. **REQ-ID linking** maintains traceability
2. **TASK-ID progression** shows implementation status
3. **ADR-ID references** connect decisions to outcomes
4. **Iteration alignment** keeps modules synchronized

---

## 📋 **Examples**

### **Requirement Processing**
```
REQ-FUNC-001: User Authentication System
├── TASK-REQ-FUNC-001-001: Design authentication flow (iteration-2)
├── TASK-REQ-FUNC-001-002: Implement login API (iteration-4)  
├── TASK-REQ-FUNC-001-003: Create UI components (iteration-5)
└── ADR-001: Authentication method selection (iteration-3)
```

### **Iteration Progression**
```
iteration-0:  Project initialization complete
iteration-1:  REQ-FUNC-001 discovered and drafted
iteration-2:  REQ-FUNC-001 refined, TASK-001 created
iteration-3:  ADR-001 completed, TASK-001 moved to active
iteration-4:  TASK-002 created, TASK-001 moved to testing
iteration-5:  TASK-001 completed, TASK-002 active
```

### **Health Monitoring**
```
iteration-10:  First review cycle
- 8 tasks completed
- 2 requirements validated  
- 1 decision implemented
- Health score: 85%

iteration-30:  Effectiveness assessment
- 24 tasks completed
- 6 requirements delivered
- 3 decisions evaluated
- Health score: 92%
```

---

## ✅ **Benefits for AI Agents**

1. **Predictable Progression**: Clear iteration-based milestones
2. **Automated Escalation**: No human time-monitoring needed  
3. **Consistent Metrics**: Iteration-based measurements across modules
4. **Scalable System**: Works regardless of project duration
5. **Cross-Reference Integrity**: Entity IDs maintain relationships
6. **Quality Assurance**: Iteration gates prevent incomplete progression

---

**AI agents operate in cycles, not calendar time. This system aligns perfectly with agent-based workflows for maximum efficiency and clarity.** 