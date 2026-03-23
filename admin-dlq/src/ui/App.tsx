
import React, { useEffect, useState } from 'react';
const BASE = (import.meta as any).env?.VITE_API_BASE ?? 'http://localhost:8080';
type Row = { id:string; tenant_id:string; topic:string; payload:any; error?:string; created_at?:string };
export function App(){
  const [rows,setRows] = useState<Row[]>([]);
  const load = ()=> fetch(`${BASE}/internal/dlq/list`).then(r=>r.ok?r.json():[]).then(setRows).catch(()=>setRows([]));
  useEffect(()=>{ load(); const t=setInterval(load, 3000); return ()=>clearInterval(t); },[]);
  const replay = async (id:string)=>{ await fetch(`${BASE}/internal/dlq/replay/${id}`, {method:'POST'}); load(); };
  const drop = async (id:string)=>{ await fetch(`${BASE}/internal/dlq/drop/${id}`, {method:'POST'}); load(); };
  return <div style={{padding:16, fontFamily:'ui-sans-serif'}}>
    <h3>Outbox DLQ</h3>
    {rows.length===0? <div>—</div> : rows.map(r=>(
      <div key={r.id} style={{border:'1px solid #ddd', borderRadius:8, padding:8, marginBottom:8}}>
        <div><b>{r.id}</b> <small>{r.topic}</small></div>
        <div style={{fontFamily:'ui-monospace', fontSize:12, whiteSpace:'pre-wrap'}}>{JSON.stringify(r.payload)}</div>
        <div style={{marginTop:6}}>
          <button onClick={()=>replay(r.id)}>Replay</button>
          <button onClick={()=>drop(r.id)} style={{marginLeft:8}}>Drop</button>
        </div>
      </div>
    ))}
  </div>;
}
