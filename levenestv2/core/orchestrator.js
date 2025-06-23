const fs = require('fs');
const path = require('path');
const yaml = require('js-yaml');
const { spawnSync } = require('child_process');

function loadYAML(filePath) {
  return yaml.load(fs.readFileSync(filePath, 'utf8'));
}

function runAgent(agentDir, inputObj) {
  const agentScript = path.join(agentDir, 'agent.js');
  if (!fs.existsSync(agentScript)) {
    throw new Error(`Agent script not found: ${agentScript}`);
  }
  // Pass input as JSON string via stdin
  const result = spawnSync('node', [agentScript], {
    input: JSON.stringify(inputObj),
    encoding: 'utf8',
    maxBuffer: 1024 * 1024
  });
  if (result.error) throw result.error;
  if (result.status !== 0) throw new Error(result.stderr || 'Agent failed');
  try {
    return JSON.parse(result.stdout);
  } catch (e) {
    throw new Error('Agent did not return valid JSON');
  }
}

function runWorkflow(workflowPath, projectPath = process.cwd()) {
  const workflow = loadYAML(workflowPath);
  const chainOfActions = [];
  let lastOutput = { projectPath };
  console.log(`Running workflow: ${workflow.name} (v${workflow.version})`);
  for (const step of workflow.steps) {
    const [agentName] = step.agent.split('@');
    const agentDir = path.join(__dirname, '../packages', agentName);
    const agentYamlPath = path.join(agentDir, 'agent.yaml');
    if (!fs.existsSync(agentYamlPath)) {
      console.error(`Agent not found: ${agentName}`);
      break;
    }
    const agent = loadYAML(agentYamlPath);
    let input = { ...lastOutput };
    let output = null;
    let error = null;
    try {
      if (fs.existsSync(path.join(agentDir, 'agent.js'))) {
        output = runAgent(agentDir, input);
      } else {
        output = { message: 'No agent.js, skipping execution.' };
      }
      console.log(`\n[Step: ${step.id}] ${agent.name}`);
      console.log('Input:', JSON.stringify(input, null, 2));
      console.log('Output:', JSON.stringify(output, null, 2));
    } catch (e) {
      error = e.message;
      console.log(`\n[Step: ${step.id}] ${agent.name} FAILED`);
      console.log('Input:', JSON.stringify(input, null, 2));
      console.log('Error:', error);
      chainOfActions.push({ step: step.id, agent: agent.name, input, output, error });
      break;
    }
    chainOfActions.push({ step: step.id, agent: agent.name, input, output, error });
    lastOutput = output;
  }
  console.log('\n=== Chain of Actions ===');
  chainOfActions.forEach((action, idx) => {
    console.log(`\n#${idx + 1} Step: ${action.step}, Agent: ${action.agent}`);
    if (action.error) {
      console.log('  ERROR:', action.error);
    } else {
      console.log('  Input:', JSON.stringify(action.input));
      console.log('  Output:', JSON.stringify(action.output));
    }
  });
  console.log('\nWorkflow complete.');
}

if (require.main === module) {
  const workflowPath = process.argv[2] || path.join(__dirname, '../packages/sample-workflow/workflow.yaml');
  const projectPath = process.argv[3] || process.cwd();
  runWorkflow(workflowPath, projectPath);
}
