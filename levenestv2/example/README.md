# Example & Testing Area

This directory is for testing workflows, agents, and platform hypotheses in levenestv2.

## How to Use

1. **Add Sample Projects:**
   - Place sample project folders here (e.g., Node.js, Python projects) to test context extraction and code generation.

2. **Run Workflows:**
   - Use the orchestrator to run workflows against these sample projects.
   - Example:
     ```bash
     node ../core/orchestrator.js ../packages/sample-workflow/workflow.yaml ./sample-node-project
     ```

3. **Interpret Results:**
   - Review the chain of actions and outputs in the console.
   - Check generated code and validation reports.

## Notes
- This area is for experimentation and hypothesis testing.
- Feel free to add/remove sample projects and workflow runs as needed. 
