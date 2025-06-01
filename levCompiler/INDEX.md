# levCompiler System Index 🤖
**AI Agent Orchestration Framework - LLM Entry Point**

> **Start Here**: This is the main entry point for AI agents interacting with the levCompiler system. Everything you need to understand and operate the system begins here.

## 🎯 Quick Start for AI Agents

### 1. **Understanding Your Role**
You are interacting with an AI agent orchestration framework. Your primary interface points are:
- **Human Interaction Agent** (HUMAN-INTERACT-001) - Your main counterpart
- **Intelligent Routing Engine** - Routes your requests to appropriate workflows
- **Quality Gate System** - Validates all outputs and decisions

### 2. **First Steps**
```bash
# Check system status
levCompiler monitor status

# Review project context (if available)
levCompiler context review

# Get context-aware help
levCompiler --help
```

## 📋 System Navigation Guide

### 🏗️ **Core Architecture** (`SYSTEM_OVERVIEW.md`)
**Read First**: Complete system understanding
- 10 Specialized agents and their capabilities
- 5 Workflow types for different scenarios
- Quality gate system and escalation paths
- Project context management

### 🤖 **Agent Specifications** (`/agents/`)
**When you need to understand agent capabilities**
```
agents/
├── production/          # 9 core production agents
├── meta/               # 2 human-supervised meta agents  
└── README.md           # Agent interaction guide
```

### 🔄 **Workflow Systems** (`/workflows/`)
**When you need to understand available workflows**
```
workflows/
├── project_analysis/   # Existing project integration
├── project_setup/     # New project creation
├── code_generation/   # Code development workflows
├── visual_development/ # Design-to-code workflows
└── base_io/           # Core orchestration pattern
```

### ⚙️ **Control Systems** (`/.control/`)
**When you need to understand system internals**
```
.control/
├── routing/           # Intelligent routing engine
├── gates/            # Quality gate management
├── orchestration/    # Workflow orchestration
└── interfaces/       # CLI and other interfaces
```

## 🎯 Common Interaction Patterns

### **Pattern 1: New User/Project Analysis**
```
1. User provides project path or description
2. Route to: project-analysis-workflow
3. Agents involved: TechStackManager → HumanInteraction
4. Output: Complete project context in project_context/
5. Follow-up: Customization recommendations
```

### **Pattern 2: Code Generation Request**
```
1. User requests code implementation
2. Route to: code-generation-workflow  
3. Agents involved: CodeGeneration → CodeReviewing → DryRunTesting → Gatekeeping
4. Quality gates: Syntax → Standards → Security → BestPractices
5. Output: Production-ready code with documentation
```

### **Pattern 3: Project Setup**
```
1. User wants new project
2. Route to: project-setup-workflow
3. Agents involved: TechStackManager → CodeGeneration → VersionTracking
4. Output: Complete project structure with best practices
```

### **Pattern 4: Visual Development**
```
1. User provides design/mockup
2. Route to: visual-development-workflow
3. Agents involved: VisualAnnotation → CodeGeneration → CodeReviewing
4. Output: Functional code from visual design
```

## 🚦 Decision Matrix for AI Agents

### **Input Classification**
| Input Type | Confidence | Route To | Escalation |
|------------|------------|----------|------------|
| Project analysis request | >0.90 | project-analysis-workflow | None |
| Code generation request | >0.85 | code-generation-workflow | CodeReviewing |
| Setup new project | >0.90 | project-setup-workflow | None |
| Visual design input | >0.80 | visual-development-workflow | HumanInteraction |
| Complex/unclear request | <0.70 | HumanInteraction | Human review |

### **Quality Gate Thresholds**
| Gate Type | Minimum Threshold | Escalation Point |
|-----------|------------------|------------------|
| Security | 0.95 | Security team |
| Syntax | 1.0 | Auto-fix |
| Standards | 0.90 | CodeReviewing agent |
| Best Practices | 0.85 | Senior review |

## 📖 Quick Reference Guides

### **Available Commands**
```bash
# Analysis Commands
levCompiler analyze project <path> [--scope comprehensive]
levCompiler analyze tech-stack <path>
levCompiler analyze quality <path>

# Generation Commands  
levCompiler generate component <name> [--props] [--styled]
levCompiler generate api <name> [--method] [--auth]
levCompiler generate test <target> [--coverage]

# Setup Commands
levCompiler setup project <name> --template <type>
levCompiler setup config --interactive

# Visual Commands
levCompiler visual analyze --input <file>
levCompiler visual implement --framework <type>

# Context Commands
levCompiler context review [--detailed]
levCompiler context update [--force] 
levCompiler context customize --agent <type>

# Monitoring Commands
levCompiler monitor status [--real-time]
levCompiler monitor workflows [--workflow-id]
levCompiler monitor quality [--time-range]
```

### **Project Context Structure**
```
project_context/
├── profile/
│   ├── project_metadata.json    # Basic project info
│   ├── team_conventions.json    # Coding standards  
│   └── technology_preferences.json
├── analysis/
│   ├── tech_stack_analysis.json # Current technology
│   ├── code_quality_metrics.json
│   └── architecture_analysis.json
├── patterns/
│   ├── design_patterns.json     # Preferred patterns
│   ├── coding_conventions.json  # Style guides
│   └── component_patterns.json
├── integration/
│   ├── agent_configurations.json # Agent customizations
│   ├── workflow_preferences.json
│   └── quality_thresholds.json
└── improvements/
    ├── recommendations.json     # Improvement suggestions
    ├── optimization_plan.json
    └── integration_roadmap.json
```

## ⚠️ Important Guidelines for AI Agents

### **Always Check First**
1. **Project Context**: Look for `project_context/` directory
2. **System Status**: Verify all agents are available
3. **Quality Requirements**: Check project-specific thresholds
4. **User Preferences**: Review team conventions and preferences

### **Quality Standards**
- **Never skip quality gates** - They ensure consistent output quality
- **Always provide context** - Include reasoning for decisions
- **Escalate when uncertain** - Use human interaction for ambiguous cases
- **Document decisions** - Maintain audit trail for all actions

### **Error Handling**
- **Graceful degradation** - Provide best possible output even with limitations
- **Clear communication** - Explain what you can/cannot do
- **Proactive escalation** - Route to human interaction before failing
- **State preservation** - Maintain context through handoffs

## 🔍 Troubleshooting Guide

### **Common Scenarios**

#### "Agent not responding"
1. Check agent availability: `levCompiler monitor agents`
2. Verify system status: `levCompiler monitor status`
3. Escalate to human interaction if persistent

#### "Quality gate failure"
1. Review specific gate failure: Check validation results
2. Apply automatic fixes if available
3. Request human review for subjective issues
4. Document lessons learned

#### "Unclear user input"
1. Use intelligent routing confidence scoring
2. If confidence < 0.70, engage human interaction
3. Ask clarifying questions before proceeding
4. Provide options when multiple interpretations possible

#### "Context missing"
1. Check for project_context/ directory
2. If missing, suggest running: `levCompiler analyze project`
3. Use system defaults while recommending context generation

## 🎯 Success Metrics for AI Agents

### **Operational Excellence**
- Routing accuracy > 95%
- Quality gate pass rate > 90%
- First-time resolution > 85%
- User satisfaction > 4.5/5

### **Quality Measures**
- Code quality scores consistently improving
- Security violations < 1%
- Performance optimizations applied
- Best practices adherence > 90%

---

## 🚀 Getting Started Checklist

For AI agents beginning interaction with levCompiler:

- [ ] Read `SYSTEM_OVERVIEW.md` for complete understanding
- [ ] Check system status and agent availability
- [ ] Identify if project context exists
- [ ] Understand user's primary intent
- [ ] Route to appropriate workflow with confidence scoring
- [ ] Apply quality gates throughout process
- [ ] Maintain clear communication with user
- [ ] Document all decisions and actions
- [ ] Escalate appropriately when needed
- [ ] Provide actionable outputs

---

## 📞 Need Help?

- **System Documentation**: Start with `SYSTEM_OVERVIEW.md`
- **Agent Specifications**: Check `/agents/README.md`
- **Workflow Details**: Review `/workflows/` directory
- **Quality Gates**: See `/.control/gates/quality_gate_system.json`
- **Routing Logic**: Check `/.control/routing/intelligent_routing_engine.json`

**Remember**: When in doubt, engage the Human Interaction Agent - it's designed to handle complex scenarios and provide guidance.

---

*This index is your compass for navigating the levCompiler system. Everything you need starts here.* 🧭 