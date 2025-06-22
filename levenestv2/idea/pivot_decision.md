# Pivot Decision: From levCompiler to levenestv2

## Context
The initial SWOT analysis and critical review of levCompiler revealed that, while technically ambitious, the system was over-engineered, rigid, and difficult to adopt for most teams. The complexity and strict process enforcement created high barriers to entry and limited practical value.

## Key Insights
- Most teams need a simple, composable, and developer-friendly solution.
- Overly rigid enforcement and bureaucracy slow down real work.
- The core value lies in context-aware, agentic workflows—not in heavy orchestration.
- Hybrid approaches (prompt + script) offer the best balance of flexibility and reliability.

## The Pivot
We decided to:
- Build a local-first, IDE-integrated platform (levenestv2)
- Use YAML for workflow definition and agent rules
- Focus on modular, versioned, and composable workflows
- Prioritize developer experience and speed over architectural purity
- Retain the best ideas (context-awareness, validation, composability) but drop the bloat

## New Direction
levenestv2 will be:
- Lightweight, fast, and easy to adopt
- Designed for real-world developer workflows
- Open to community-contributed workflows and agents
- Focused on solving the core problem: reliable, context-aware code generation and problem solving 
