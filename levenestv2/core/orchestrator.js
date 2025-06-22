const fs = require('fs');
const path = require('path');
const yaml = require('js-yaml');

function loadYAML(filePath) {
  return yaml.load(fs.readFileSync(filePath, 'utf8'));
}

function runWorkflow(workflowPath) {
  const workflow = loadYAML(workflowPath);
  console.log(`Running workflow: ${workflow.name} (v${workflow.version})`);
  for (const step of workflow.steps) {
    const [agentName] = step.agent.split('@');
    const agentPath = path.join(__dirname, '../packages', agentName, 'agent.yaml');
    if (!fs.existsSync(agentPath)) {
      console.error(`Agent not found: ${agentName}`);
      continue;
    }
    const agent = loadYAML(agentPath);
    console.log(`\n[Step: ${step.id}] Using agent: ${agent.name} (v${agent.version})`);
    console.log(`Description: ${agent.description}`);
    // Mock execution
    console.log(`--> Executing ${agent.name}... (mock)`);
  }
  console.log('\nWorkflow complete.');
}

if (require.main === module) {
  const workflowPath = process.argv[2] || path.join(__dirname, '../packages/sample-workflow/workflow.yaml');
  runWorkflow(workflowPath);
}
